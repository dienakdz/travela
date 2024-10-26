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



}
