<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TourDetailController extends Controller
{
    public function index($id='')
    {
        $title = 'Chi tiết tours' . $id;
        return view('clients.tour-detail', compact('title'));
    }
}
