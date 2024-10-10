<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TourDetailController extends Controller
{
    public function index(){
        return view('clients.tour-detail');
    }
}
