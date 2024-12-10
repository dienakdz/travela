<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Illuminate\Http\Request;

class MyTourController extends Controller
{
    private $tours;

    public function __construct()
    {
        parent::__construct(); // Gọi constructor của Controller để khởi tạo $user
        $this->tours = new Tours();
    }

    public function index()
    {
        $title = 'Tours đã đặt';
        $userId = $this->getUserId();
        
        $myTours = $this->user->getMyTours($userId);
        $toursPopular = $this->tours->toursPopular(2);

        // dd($myTours);

        return view('clients.my-tours', compact('title', 'myTours','toursPopular'));
    }
}
