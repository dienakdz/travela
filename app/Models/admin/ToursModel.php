<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ToursModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_tours';

    public function getAllTours()
    {
        return DB::table($this->table)
            ->orderBy('tourId', 'DESC')
            ->get();
    }

    public function createTours($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function uploadImages($data)
    {
        return DB::table('tbl_images')->insert($data);
    }

    public function uploadTempImages($data)
    {
        return DB::table('tbl_temp_images')->insert($data);
    }

    public function addTimeLine($data)
    {
        return DB::table('tbl_timeline')->insert($data);
    }

    public function updateTour($tourId,$data){
        $updated = DB::table($this->table)
        ->where('tourId',$tourId)
        ->update($data);

        return $updated;
    }
    public function deleteTour($tourId)
    {
        // Xóa các dữ liệu liên quan trong bảng 'tbl_timeline' và 'tbl_images'
        $deleteTimeLine = DB::table('tbl_timeline')->where('tourId', $tourId)->delete();
        $deleteImages = DB::table('tbl_images')->where('tourId', $tourId)->delete();

        if ($deleteTimeLine && $deleteImages) {
            $deleteTour = DB::table($this->table)->where('tourId', $tourId)->delete();

            // Trả về kết quả xóa tour
            if ($deleteTour) {
                return ['success' => true, 'message' => 'Tour đã được xóa thành công.'];
            } else {
                return ['success' => false, 'message' => 'Không thể xóa tour chính.'];
            }
        } else {
            return ['success' => false, 'message' => 'Không thể xóa các dữ liệu liên quan (timeline, images).'];
        }
    }

    public function getTour($tourId){
        return DB::table($this->table)->where('tourId', $tourId)->first();
    }

    public function getImages($tourId){
        return DB::table('tbl_images')->where('tourId', $tourId)->get();
    }

    public function getTimeLine($tourId){
        return DB::table('tbl_timeline')->where('tourId', $tourId)->get();
    }

    public function deleteData($tourId, $tbl){
        return DB::table($tbl)->where('tourId', $tourId)->delete();
    }

}
