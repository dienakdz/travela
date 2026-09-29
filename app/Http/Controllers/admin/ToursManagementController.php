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

    public function addTours(Request $request)
    {
        $name = $request->input('name');
        $destination = $request->input('destination');
        $domain = $request->input('domain');
        $quantity = $request->input('number');
        $price_adult = $request->input('price_adult');
        $price_child = $request->input('price_child');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $description = $request->input('description');


        // Chuyển start_date và end_date từ định dạng d/m/Y sang Y-m-d
        $startDate = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

        // Tính số ngày giữa start_date và end_date
        $days = Carbon::createFromFormat('Y-m-d', $startDate)->diffInDays(Carbon::createFromFormat('Y-m-d', $endDate));

        // Tính số đêm: số ngày - 1
        $nights = $days - 1;

        // Định dạng thời gian theo kiểu "X ngày Y đêm"
        $time = "{$days} ngày {$nights} đêm";


        $dataTours = [
            'title' => $name,
            'time' => $time,
            'description' => $description,
            'quantity' => $quantity,
            'priceAdult' => $price_adult,
            'priceChild' => $price_child,
            'destination' => $destination,
            'domain' => $domain,
            'availability' => 0,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        // dd($dataTours);

        $createTour = $this->tours->createTours($dataTours);

        // dd($createTour);
        return response()->json([
            'success' => true,
            'message' => 'Tour added successfully!',
            'tourId' => $createTour
        ]);

    }

    public function addImagesTours(Request $request)
    {
        try {
            $image = $request->file('image');
            $tourId = $request->tourId;

            // Kiểm tra xem file có hợp lệ không
            if (!$image->isValid()) {
                return response()->json(['success' => false, 'message' => 'Invalid file upload'], 400);
            }

            // Lấy tên gốc của file (không bao gồm đường dẫn)
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            // Lấy phần mở rộng của file
            $extension = $image->getClientOriginalExtension();

            // Tạo tên file mới: [original_name]_[timestamp].[extension]
            $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName) . '_' . time() . '.' . $extension;

            // Resize hình ảnh về kích thước 400x350
            $resizedImage = Image::make($image)->resize(400, 350);

            // Di chuyển file vào thư mục đích
            $destinationPath = public_path('admin/assets/images/gallery-tours/');
            $resizedImage->save($destinationPath . $filename); // Lưu ảnh đã resize

            // Tạo dữ liệu để lưu vào cơ sở dữ liệu
            $dataUpload = [
                'tourId' => $tourId,
                'imageURL' => $filename,
                'description' => $originalName
            ];

            // Lưu thông tin vào cơ sở dữ liệu
            $uploadImage = $this->tours->uploadImages($dataUpload);

            // Kiểm tra kết quả lưu trữ
            if ($uploadImage) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => [
                        'filename' => $filename,
                        'tourId' => $tourId
                    ]
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Failed to save image data'], 500);
        } catch (\Exception $e) {
            // Xử lý lỗi bất ngờ
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addTimeline(Request $request)
    {
        $tourId = $request->tourId;

        // Tạo một mảng chứa các timeline
        $timelines = [];

        // Lặp qua tất cả các keys trong request để tìm các cặp `day-X` và `itinerary-X`
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^day-(\d+)$/', $key, $matches)) {
                $dayNumber = $matches[1]; // Lấy số ngày (X) từ `day-X`

                // Tìm `itinerary-X` tương ứng
                $itineraryKey = "itinerary-{$dayNumber}";
                if ($request->has($itineraryKey)) {
                    $timelines[] = [
                        'tourId' => $tourId,
                        'title' => $value,
                        'description' => $request->input($itineraryKey),
                    ];
                }
            }
        }

        foreach ($timelines as $timeline) {
            $this->tours->addTimeLine($timeline);
        }
        $dataUpdate = [
            'availability' => 1
        ];

        $updateAvailability = $this->tours->updateTour($tourId, $dataUpdate);
        toastr()->success('Thêm tour thành công!');
        return redirect()->route('admin.page-add-tours');
    }

    public function getTourEdit(Request $request)
    {
        $tourId = $request->tourId;

        $getTour = $this->tours->getTour($tourId);
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
        if ($getTour) {
            return response()->json([
                'success' => true,
                'tour' => $getTour,
                'images' => $getImages,
                'timeline' => $getTimeLine
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function uploadTempImagesTours(Request $request)
    {
        try {
            $image = $request->file('image');
            $tourId = $request->tourId;

            // Kiểm tra xem file có hợp lệ không
            if (!$image->isValid()) {
                return response()->json(['success' => false, 'message' => 'Invalid file upload'], 400);
            }

            // Lấy tên gốc của file (không bao gồm đường dẫn)
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            // Lấy phần mở rộng của file
            $extension = $image->getClientOriginalExtension();

            // Tạo tên file mới: [original_name]_[timestamp].[extension]
            $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName) . '_' . time() . '.' . $extension;

            // Resize hình ảnh về kích thước 400x350
            $resizedImage = Image::make($image)->resize(400, 350);

            // Di chuyển file vào thư mục đích
            $destinationPath = public_path('admin/assets/images/gallery-tours/');
            $resizedImage->save($destinationPath . $filename); // Lưu ảnh đã resize

            // Tạo dữ liệu để lưu vào cơ sở dữ liệu
            $dataUpload = [
                'tourId' => $tourId,
                'imageTempURL' => $filename,
            ];

            // Lưu thông tin vào cơ sở dữ liệu
            $uploadImage = $this->tours->uploadTempImages($dataUpload);

            // Kiểm tra kết quả lưu trữ
            if ($uploadImage) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => [
                        'filename' => $filename,
                        'tourId' => $tourId
                    ]
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Failed to save image data'], 500);
        } catch (\Exception $e) {
            // Xử lý lỗi bất ngờ
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateTour(Request $request)
    {
        $tourId = $request->tourId;
        $name = $request->input('name');
        $destination = $request->input('destination');
        $domain = $request->input('domain');
        $quantity = $request->input('number');
        $price_adult = $request->input('price_adult');
        $price_child = $request->input('price_child');
        $description = $request->input('description');

        $dataTours = [
            'title'       => $name,
            'description' => $description,
            'quantity'    => $quantity,
            'priceAdult'  => $price_adult,
            'priceChild'  => $price_child,
            'destination' => $destination,
            'domain'      => $domain,
        ];

        $delete_timeline = $this->tours->deleteData($tourId, 'tbl_timeline');
        $delete_images = $this->tours->deleteData($tourId, 'tbl_images');

        $updateTour = $this->tours->updateTour($tourId, $dataTours);

        // Tạo mảng tạm để lưu tên ảnh
        $images = $request->input('images');  // Mảng các tên ảnh gửi lên từ request

        if ($images && is_array($images)) {
            foreach ($images as $image) {
                $dataUpload = [
                    'tourId' => $tourId,
                    'imageURL' => $image, 
                    'description' => $name  
                ];
                $this->tours->uploadImages($dataUpload);
            }
        }

        $timelines = $request->input('timeline');

        if ($timelines && is_array($timelines)) {
            foreach ($timelines as $timeline) {
                $data = [
                    'tourId' => $tourId,
                    'title' => $timeline['title'],
                    'description' => $timeline['itinerary']
                ];

                $this->tours->addTimeLine($data);  // Gọi phương thức addTimeLine()
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sửa thành công!',
        ]);

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
