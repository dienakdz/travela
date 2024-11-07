<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyTourController extends Controller
{
    //

    public function __construct()
    {
        parent::__construct(); // Gọi constructor của Controller để khởi tạo $user
    }

    public function index()
    {
        $title = 'Tours đã đặt';
        $userId = $this->getUserId();
        
        $myTours = $this->user->getMyTours($userId);

        // dd($myTours);

        return view('clients.my-tours', compact('title', 'myTours'));
    }
}
