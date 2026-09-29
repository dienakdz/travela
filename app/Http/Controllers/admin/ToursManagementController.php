<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\ToursModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RuntimeException;
use Throwable;

class ToursManagementController extends Controller
{
    private $tours;

    public function __construct()
    {
        $this->tours = new ToursModel();
    }
    public function index()
    {
        $title = 'Quản lý Tours';

        $tours = $this->tours->getAllTours();
        return view('admin.tours', compact('title', 'tours'));
    }

    public function pageAddTours()
    {
        $title = 'Thêm Tours';

        return view('admin.add-tours', compact('title'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'in:b,t,n'],
            'number' => ['required', 'integer', 'min:1'],
            'price_adult' => ['required', 'numeric', 'min:0'],
            'price_child' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
            'description' => ['required', 'string'],
            'images' => ['required', 'array', 'size:5'],
            'images.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'timelines' => ['required', 'array', 'min:1'],
            'timelines.*.title' => ['required', 'string', 'max:255'],
            'timelines.*.description' => ['required', 'string'],
        ], [
            'images.size' => 'Tour phải có đúng :size hình ảnh.',
            'images.*.image' => 'Mỗi tệp tải lên phải là một hình ảnh hợp lệ.',
            'images.*.mimes' => 'Ảnh chỉ được dùng định dạng JPEG, JPG, PNG hoặc WEBP.',
            'images.*.max' => 'Mỗi ảnh không được lớn hơn 5 MB.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày khởi hành.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->hasAny(['start_date', 'end_date', 'timelines'])) {
                return;
            }

            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
            $maximumTimelineDays = $startDate->diffInDays($endDate);

            if (count($request->input('timelines', [])) > $maximumTimelineDays) {
                $validator->errors()->add(
                    'timelines',
                    "Không thể thêm quá {$maximumTimelineDays} ngày cho tour này."
                );
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tourId = $this->createTourAtomically($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Thêm tour thành công!',
                'tourId' => $tourId,
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Không thể thêm tour. Vui lòng thử lại.',
            ], 500);
        }
    }

    public function getTourEdit(Request $request)
    {
        $tourId = $request->tourId;

        $getTour = $this->tours->getTour($tourId);

        if (!$getTour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour không tồn tại.',
            ], 404);
        }

        // Lấy ngày bắt đầu của tour và ngày hiện tại
        $startDate = Carbon::parse($getTour->startDate); // Chuyển đổi ngày bắt đầu sang đối tượng Carbon
        $today = Carbon::now(); // Lấy ngày hiện tại

        // Kiểm tra nếu ngày bắt đầu <= hôm nay
        if ($startDate->lessThanOrEqualTo($today)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chỉnh sửa vì tour đã hoặc đang diễn ra.',
            ]);
        }


        $getImages = $this->tours->getImages($tourId);
        $getTimeLine = $this->tours->getTimeLine($tourId);
        return response()->json([
            'success' => true,
            'tour' => $getTour,
            'images' => $getImages,
            'timeline' => $getTimeLine
        ]);
    }

    public function updateTour(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tourId' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'in:b,t,n'],
            'number' => ['required', 'integer', 'min:1'],
            'price_adult' => ['required', 'numeric', 'min:0'],
            'price_child' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'existing_images' => ['sometimes', 'array', 'max:5'],
            'existing_images.*' => ['required', 'string', 'max:255', 'distinct'],
            'new_images' => ['sometimes', 'array', 'max:5'],
            'new_images.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'timelines' => ['required', 'array', 'min:1'],
            'timelines.*.title' => ['required', 'string', 'max:255'],
            'timelines.*.description' => ['required', 'string'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->has('tourId')) {
                return;
            }

            $tour = $this->tours->getTour($request->tourId);

            if (!$tour) {
                $validator->errors()->add('tourId', 'Tour không tồn tại.');
                return;
            }

            if (Carbon::parse($tour->startDate)->lessThanOrEqualTo(Carbon::today())) {
                $validator->errors()->add('tourId', 'Không thể sửa tour đã hoặc đang diễn ra.');
            }

            $existingImages = $request->input('existing_images', []);

            if (!is_array($existingImages)) {
                $existingImages = [];
            }

            $currentImages = $this->tours
                ->getImages($request->tourId)
                ->pluck('imageURL')
                ->all();

            if (array_diff($existingImages, $currentImages)) {
                $validator->errors()->add(
                    'existing_images',
                    'Danh sách ảnh hiện tại của tour không hợp lệ.'
                );
            }

            $newImages = $request->file('new_images', []);

            if (!is_array($newImages)) {
                $newImages = [];
            }

            if (count($existingImages) + count($newImages) !== 5) {
                $validator->errors()->add('images', 'Tour phải có đúng 5 hình ảnh.');
            }

            $maximumTimelineDays = Carbon::parse($tour->startDate)
                ->diffInDays(Carbon::parse($tour->endDate));
            $timelines = $request->input('timelines', []);

            if (is_array($timelines) && count($timelines) > $maximumTimelineDays) {
                $validator->errors()->add(
                    'timelines',
                    "Không thể thêm quá {$maximumTimelineDays} ngày cho tour này."
                );
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $this->updateTourAtomically($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sửa thành công!',
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Không thể sửa tour. Vui lòng thử lại.',
            ], 500);
        }

    }

    public function deleteTour(Request $request)
    {
        $tourId = $request->tourId;

        $result = $this->tours->deleteTour($tourId);
        $tours = $this->tours->getAllTours();
        // Kiểm tra kết quả trả về từ Model
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => view('admin.partials.list-tours', compact('tours'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }

    private function createTourAtomically(array $data)
    {
        $temporaryRoot = config('tours.images.temporary_path', storage_path('app/tmp/tours'));
        $finalDirectory = config(
            'tours.images.path',
            public_path('admin/assets/images/gallery-tours')
        );
        $requestDirectory = rtrim($temporaryRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . Str::uuid();
        $stagedImages = [];
        $movedPaths = [];

        try {
            File::ensureDirectoryExists($requestDirectory);
            $stagedImages = $this->stageTourImages($data['images'], $requestDirectory);

            return DB::transaction(function () use ($data, $stagedImages, $finalDirectory, &$movedPaths) {
                $startDate = Carbon::createFromFormat('Y-m-d', $data['start_date']);
                $endDate = Carbon::createFromFormat('Y-m-d', $data['end_date']);
                $days = $startDate->diffInDays($endDate);
                $nights = $days - 1;

                $tourId = $this->tours->createTours([
                    'title' => $data['name'],
                    'time' => "{$days} ngày {$nights} đêm",
                    'description' => $data['description'],
                    'quantity' => $data['number'],
                    'priceAdult' => $data['price_adult'],
                    'priceChild' => $data['price_child'],
                    'destination' => $data['destination'],
                    'domain' => $data['domain'],
                    'availability' => 1,
                    'startDate' => $data['start_date'],
                    'endDate' => $data['end_date'],
                ]);

                File::ensureDirectoryExists($finalDirectory);

                foreach ($stagedImages as $image) {
                    $finalPath = $finalDirectory . DIRECTORY_SEPARATOR . $image['filename'];

                    if (!File::move($image['temporary_path'], $finalPath)) {
                        throw new RuntimeException('Không thể lưu hình ảnh của tour.');
                    }

                    $movedPaths[] = $finalPath;

                    $imageCreated = $this->tours->uploadImages([
                        'tourId' => $tourId,
                        'imageURL' => $image['filename'],
                        'description' => $image['description'],
                    ]);

                    if (!$imageCreated) {
                        throw new RuntimeException('Không thể lưu thông tin hình ảnh của tour.');
                    }
                }

                foreach ($data['timelines'] as $timeline) {
                    $timelineCreated = $this->tours->addTimeLine([
                        'tourId' => $tourId,
                        'title' => $timeline['title'],
                        'description' => $timeline['description'],
                    ]);

                    if (!$timelineCreated) {
                        throw new RuntimeException('Không thể lưu lộ trình của tour.');
                    }
                }

                return $tourId;
            });
        } catch (Throwable $exception) {
            foreach ($movedPaths as $path) {
                File::delete($path);
            }

            throw $exception;
        } finally {
            File::deleteDirectory($requestDirectory);
        }
    }

    private function updateTourAtomically(array $data)
    {
        $temporaryRoot = config('tours.images.temporary_path', storage_path('app/tmp/tours'));
        $finalDirectory = config(
            'tours.images.path',
            public_path('admin/assets/images/gallery-tours')
        );
        $requestDirectory = rtrim($temporaryRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . Str::uuid();
        $existingImages = $data['existing_images'] ?? [];
        $removedImages = [];
        $stagedImages = [];
        $movedPaths = [];

        try {
            File::ensureDirectoryExists($requestDirectory);
            $stagedImages = $this->stageTourImages(
                $data['new_images'] ?? [],
                $requestDirectory
            );

            DB::transaction(function () use (
                $data,
                $existingImages,
                $stagedImages,
                $finalDirectory,
                &$movedPaths,
                &$removedImages
            ) {
                $tour = DB::table('tbl_tours')
                    ->where('tourId', $data['tourId'])
                    ->lockForUpdate()
                    ->first();

                if (!$tour) {
                    throw new RuntimeException('Tour không tồn tại.');
                }

                $currentImages = $this->tours
                    ->getImages($data['tourId'])
                    ->pluck('imageURL')
                    ->all();

                if (array_diff($existingImages, $currentImages)) {
                    throw new RuntimeException('Danh sách ảnh của tour đã thay đổi.');
                }

                $removedImages = array_diff($currentImages, $existingImages);

                $this->tours->deleteData($data['tourId'], 'tbl_timeline');
                $this->tours->deleteData($data['tourId'], 'tbl_images');

                $this->tours->updateTour($data['tourId'], [
                    'title' => $data['name'],
                    'description' => $data['description'],
                    'quantity' => $data['number'],
                    'priceAdult' => $data['price_adult'],
                    'priceChild' => $data['price_child'],
                    'destination' => $data['destination'],
                    'domain' => $data['domain'],
                ]);

                foreach ($existingImages as $filename) {
                    $imageCreated = $this->tours->uploadImages([
                        'tourId' => $data['tourId'],
                        'imageURL' => $filename,
                        'description' => $data['name'],
                    ]);

                    if (!$imageCreated) {
                        throw new RuntimeException('Không thể giữ lại hình ảnh của tour.');
                    }
                }

                File::ensureDirectoryExists($finalDirectory);

                foreach ($stagedImages as $image) {
                    $finalPath = $finalDirectory . DIRECTORY_SEPARATOR . $image['filename'];

                    if (!File::move($image['temporary_path'], $finalPath)) {
                        throw new RuntimeException('Không thể lưu hình ảnh mới của tour.');
                    }

                    $movedPaths[] = $finalPath;

                    $imageCreated = $this->tours->uploadImages([
                        'tourId' => $data['tourId'],
                        'imageURL' => $image['filename'],
                        'description' => $image['description'],
                    ]);

                    if (!$imageCreated) {
                        throw new RuntimeException('Không thể lưu thông tin hình ảnh mới của tour.');
                    }
                }

                foreach ($data['timelines'] as $timeline) {
                    $timelineCreated = $this->tours->addTimeLine([
                        'tourId' => $data['tourId'],
                        'title' => $timeline['title'],
                        'description' => $timeline['description'],
                    ]);

                    if (!$timelineCreated) {
                        throw new RuntimeException('Không thể lưu lộ trình của tour.');
                    }
                }
            });

            foreach ($removedImages as $filename) {
                $isStillUsed = DB::table('tbl_images')
                    ->where('imageURL', $filename)
                    ->exists();

                if (!$isStillUsed && $filename === basename($filename)) {
                    File::delete($finalDirectory . DIRECTORY_SEPARATOR . $filename);
                }
            }
        } catch (Throwable $exception) {
            foreach ($movedPaths as $path) {
                File::delete($path);
            }

            throw $exception;
        } finally {
            File::deleteDirectory($requestDirectory);
        }
    }

    private function stageTourImages(array $images, $requestDirectory)
    {
        $stagedImages = [];

        foreach ($images as $image) {
            $filename = Str::uuid() . '.' . $image->extension();
            $temporaryPath = $requestDirectory . DIRECTORY_SEPARATOR . $filename;
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            Image::make($image)
                ->resize(400, 350)
                ->save($temporaryPath);

            $stagedImages[] = [
                'filename' => $filename,
                'temporary_path' => $temporaryPath,
                'description' => $originalName,
            ];
        }

        return $stagedImages;
    }

}
