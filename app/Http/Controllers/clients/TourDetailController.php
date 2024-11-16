<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TourDetailController extends Controller
{

    private $tours;

    public function __construct()
    {
        parent::__construct(); // Gọi constructor của Controller để khởi tạo $user
        $this->tours = new Tours();
    }
    public function index($id = 0)
    {
        $title = 'Chi tiết tours';
        $userId = $this->getUserId();

        $tourDetail = $this->tours->getTourDetail($id);
        $getReviews = $this->tours->getReviews($id);
        $reviewStats = $this->tours->reviewStats($id);

        $avgStar = round($reviewStats->averageRating);
        $countReview = $reviewStats->reviewCount;

        $checkReviewExist = $this->tours->checkReviewExist($id, $userId);
        if (!$checkReviewExist) {
            $checkDisplay = '';
        } else {
            $checkDisplay = 'hide';
        }

        
        // Gọi API Python để lấy danh sách tour liên quan
        try {
            $apiUrl = 'http://127.0.0.1:5555/api/tour-recommendations';
            $response = Http::get($apiUrl, [
                'tour_id' => $id
            ]);

            if ($response->successful()) {
                $relatedTours = $response->json('related_tours');
            } else {
                $relatedTours = [];
            }
        } catch (\Exception $e) {
            // Xử lý lỗi khi gọi API
            $relatedTours = [];
            \Log::error('Lỗi khi gọi API liên quan: ' . $e->getMessage());
        }

        $id_toursRe = $relatedTours;

        $tourRecommendations = $this->tours->toursRecommendation($id_toursRe);
        // dd($tourRecommendations);    
        // dd($avgStar);

        return view('clients.tour-detail', compact('title', 'tourDetail', 'getReviews', 'avgStar', 'countReview', 'checkDisplay','tourRecommendations'));
    }

    public function reviews(Request $req)
    {
        // dd($req);
        $userId = $this->getUserId();
        $tourId = $req->tourId;
        $message = $req->message;
        $star = $req->rating;

        $dataReview = [
            'tourId' => $tourId,
            'userId' => $userId,
            'comment' => $message,
            'rating' => $star
        ];

        $rating = $this->tours->createReviews($dataReview);
        if (!$rating) {
            return response()->json([
                'error' => true
            ], 500);
        }
        $tourDetail = $this->tours->getTourDetail($tourId);
        $getReviews = $this->tours->getReviews($tourId);
        $reviewStats = $this->tours->reviewStats($tourId);

        $avgStar = round($reviewStats->averageRating);
        $countReview = $reviewStats->reviewCount;

        // Trả về phản hồi thành công
        return response()->json([
            'success' => true,
            'message' => 'Đánh giá của bạn đã được gửi thành công!',
            'data' => view('clients.partials.reviews', compact('tourDetail', 'getReviews', 'avgStar', 'countReview'))->render()
        ], 200);
    }
}
