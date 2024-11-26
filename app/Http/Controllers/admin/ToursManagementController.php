<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\ToursModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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

        $updateAvailability = $this->tours->updateTour($tourId , $dataUpdate);
        toastr()->success('Thêm tour thành công!');
        return redirect()->route('admin.page-add-tours');
    }


    public function deleteTour(Request $request)
    {
        $tourId = $request->tourId;

        $result  = $this->tours->deleteTour($tourId);
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

}
