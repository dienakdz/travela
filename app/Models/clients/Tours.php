<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tours extends Model
{
    use HasFactory;

    protected $table = 'tbl_tours';

    //Lấy tất cả tours
    public function getAllTours()
    {

        $allTours = DB::table($this->table)->get();
        foreach ($allTours as $tour) {
            // Lấy danh sách hình ảnh thuộc về tour
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageUrl');
        }

        return $allTours;
    }
    //Lấy chi tiết tour
    public function getTourDetail($id)
    {
        $getTourDetail = DB::table($this->table)
            ->where('tourId', $id)
            ->first();

        if ($getTourDetail) {
            // Lấy danh sách hình ảnh thuộc về tour
            $getTourDetail->images = DB::table('tbl_images')
                ->where('tourId', $getTourDetail->tourId)
                ->limit(5)
                ->pluck('imageUrl');

            // Lấy danh sách timeline thuộc về tour
            $getTourDetail->timeline = DB::table('tbl_timeline')
                ->where('tourId', $getTourDetail->tourId)
                ->get();
        }


        return $getTourDetail;
    }

    //Lấy khu vực đến Bắc - Trung - Nam
    function getDomain()
    {
        return DB::table($this->table)
            ->select('domain', DB::raw('COUNT(*) as count'))
            ->whereIn('domain', ['b', 't', 'n'])
            ->groupBy('domain')
            ->get();
    }

    //Filter tours
    public function filterTours($filters = [], $sorting = null, $perPage = null)
    {
        DB::enableQueryLog();
        $getTours = DB::table($this->table);

        // Áp dụng bộ lọc nếu có
        if (!empty($filters)) {
            $getTours = $getTours->where($filters);
        }

        // Xử lý sắp xếp nếu có
        if (!empty($sorting) && isset($sorting[0][0]) && isset($sorting[0][1])) {
            $getTours = $getTours->orderBy($sorting[0][0], $sorting[0][1]);
        }
        // Thực hiện truy vấn để ghi log
        $tours = $getTours->get(); // Thực thi truy vấn để ghi log

        // In ra câu lệnh SQL đã ghi lại
        $queryLog = DB::getQueryLog();


        foreach ($tours as $tour) {
            // Lấy danh sách hình ảnh thuộc về tour
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageUrl');
        }
        // dd($queryLog); // In ra log truy vấn
        return $tours;
    }

    public function updateTours($tourId, $data)
    {
        $update = DB::table($this->table)
            ->where('tourId', $tourId)
            ->update($data);

        return $update;
    }

    //Lấy detail tour đã đặt

    public function tourBooked($bookingId, $checkoutId)
    {
        $booked = DB::table($this->table)
            ->join('tbl_booking', 'tbl_tours.tourId', '=', 'tbl_booking.tourId')
            ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
            ->where('tbl_booking.bookingId', '=', $bookingId)
            ->where('tbl_checkout.checkoutId', '=', $checkoutId)
            ->first();

            return $booked;
    }


    //Tạo đánh giá về tours
    public function createReviews($data){
        return DB::table('tbl_reviews')->insert($data);
    }

    //Lấy danh sách nội dung reviews 
    public function getReviews($id){
        $getReviews = DB::table('tbl_reviews')
        ->join('tbl_users', 'tbl_users.userId', '=', 'tbl_reviews.userId')
        ->where('tourId', $id)
        ->orderBy('tbl_reviews.timestamp','desc')
        ->take(3)
        ->get();

        return $getReviews;
    }

    //Lấy số lượng đánh giá và số sao trung bình của tour
    public function reviewStats($id){
        $reviewStats = DB::table('tbl_reviews')
        ->where('tourId', $id)
        ->selectRaw('AVG(rating) as averageRating, COUNT(*) as reviewCount')
        ->first();

        return $reviewStats;
    }

    //Kiểm tra xem người dùng đã đánh giá tour này hay chưa?
    
    public function checkReviewExist($tourId, $userId)
    {
        return DB::table('tbl_reviews')
        ->where('tourId', $tourId)
        ->where('userId', $userId)
        ->exists(); // Trả về true nếu bản ghi tồn tại, false nếu không tồn tại
    }
}
