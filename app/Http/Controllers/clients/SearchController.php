<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    private $tours;

    public function __construct()
    {
        $this->tours = new Tours();
    }
    public function index(Request $request)
    {
        $title = 'Tìm kiếm';

        $destinationMap = [
            'dn' => 'Đà Nẵng',
            'cd' => 'Côn Đảo',
            'hn' => 'Hà Nội',
            'hcm' => 'TP. Hồ Chí Minh',
            'hl' => 'Hạ Long',
            'nb' => 'Ninh Bình',
            'pq' => 'Phú Quốc',
            'dl' => 'Đà Lạt',
            'qt' => 'Quảng Trị',
            'kh' => 'Khánh Hòa',
            'ct' => 'Cần Thơ',
            'vt' => 'Vũng Tàu',
            'qn' => 'Quảng Ninh',
            'la' => 'Lào Cai',
            'bd' => 'Bình Định',
        ];

        $destination = $request->input('destination');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Chuyển đổi định dạng ngày tháng
        $formattedStartDate = $startDate ? Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d') : null;
        $formattedEndDate = $endDate ? Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d') : null;

        // Chuyển đổi giá trị sang tên chi tiết nếu có trong mảng
        $destinationName = $destinationMap[$destination];

        $dataSearch = [
            'destination' => $destinationName,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
        ];

        $tours = $this->tours->searchTours($dataSearch);

        // dd($tours);

        return view('clients.search', compact('title', 'tours'));
    }

    public function searchTours(Request $request)
    {
        $title = 'Tìm kiếm';

        $keyword = $request->input('keyword');

        // Gọi API Python đã xử lý để lấy danh sách tour tìm kiếm
        try {
            $apiUrl = 'http://127.0.0.1:5555/api/search-tours';
            $response = Http::get($apiUrl, [
                'keyword' => $keyword
            ]);

            if ($response->successful()) {
                $resultTours = $response->json('related_tours');
            } else {
                $resultTours = [];
            }
        } catch (\Exception $e) {
            // Xử lý lỗi khi gọi API
            $resultTours = [];
            \Log::error('Lỗi khi gọi API liên quan: ' . $e->getMessage());
        }

        // dd($resultTours);
        if ($resultTours) {
            $tours = $this->tours->toursSearch($resultTours);

        } else {
            $dataSearch = [
                'keyword' => $keyword
            ];
            $tours = $this->tours->searchTours($dataSearch);
        }

        // dd($tours);

        return view('clients.search', compact('title', 'tours'));
    }
}
