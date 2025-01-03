<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Home;
use App\Models\clients\Tours;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isEmpty;

class HomeController extends Controller
{
    private $homeTours;
    private $tours;

    public function __construct()
    {
        parent::__construct();
        $this->homeTours = new Home();
        $this->tours = new Tours();
    }
    public function index()
    {
        $title = 'Trang chủ';
        $tours = $this->homeTours->getHomeTours();

        $userId = $this->getUserId();
        if ($userId) {
            
            // Gọi API Python để lấy danh sách tour được gợi ý cho từng người dùng 
            try {
                $apiUrl = 'http://127.0.0.1:5555/api/user-recommendations';
                $response = Http::get($apiUrl, [
                    'user_id' => $userId
                ]);

                if ($response->successful()) {
                    $tourIds = $response->json('recommended_tours');
                } else {
                    $tourIds = [];
                }
            } catch (\Exception $e) {
                // Xử lý lỗi khi gọi API
                $tourIds = [];
                \Log::error('Lỗi khi gọi API liên quan: ' . $e->getMessage());
            }

            $toursPopular = $this->tours->toursRecommendation($tourIds);

            if (empty($tourIds)) {
                $toursPopular = $this->tours->toursPopular(6);
                
            }

            // dd($toursPopular);
        }else {
            $toursPopular = $this->tours->toursPopular(6);
        }

        // dd($toursPopular);
        return view('clients.home', compact('title', 'tours', 'toursPopular'));
    }


}
