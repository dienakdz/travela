<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\BookingModel;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{

    private $booking;

    public function __construct(){
        $this->booking = new BookingModel();
    }
    public function index(){
        $title = 'Quản lý đặt Tour';

        $list_booking = $this->booking->getBooking();

        // dd($list_booking);

        return view('admin.booking', compact('title','list_booking'));
    }

    public function confirmBooking(Request $request)
    {
        $bookingId = $request->bookingId;
        
        $dataConfirm = [
            'bookingStatus' => 'y'
        ];
        
        $result = $this->booking->updateBooking($bookingId, $dataConfirm);
        
        if ($result) {
            $list_booking = $this->booking->getBooking();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.',
                'data' => view('admin.partials.list-booking', compact('list_booking'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thất bại.'
            ], 500);
        }
    }

    public function showDetail($bookingId){
        $title = 'Chi tiết đơn đặt';

        $invoice_booking = $this->booking->getInvoiceBooking($bookingId);
        // dd($bookingId);
        if($invoice_booking->transactionId ==  null)
        {
            $invoice_booking->transactionId = 'Thanh toán tại công ty Travela';
        }
        return view('admin.booking-detail', compact('title', 'invoice_booking'));
    }
}
