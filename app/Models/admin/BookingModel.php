<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BookingModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking';

    public function getBooking(){

        $list_booking = DB::table($this->table)
        ->join('tbl_tours', 'tbl_tours.tourId', '=', 'tbl_booking.tourId')
        ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
        ->orderByDesc('bookingDate')
        ->get();

        return $list_booking;
    }

    public function updateBooking($bookingId, $data){
        return DB::table($this->table)
        ->where('bookingId',$bookingId)
        ->update($data);
    }

    public function getInvoiceBooking($bookingId){

        $invoice = DB::table($this->table)
        ->join('tbl_tours', 'tbl_tours.tourId', '=', 'tbl_booking.tourId')
        ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
        ->where('tbl_booking.bookingId', $bookingId)
        ->first();

        return $invoice;
    }

    public function updateCheckout($bookingId, $data){
        return DB::table('tbl_checkout')
        ->where('bookingId',$bookingId)
        ->update($data);
    }
}
