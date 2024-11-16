<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    private $tours;
    public function __construct(){
        $this->tours = new Tours();
    }

    public function index()
    {
        $title = 'Điểm đến';
        $tours = $this->tours->getAllTours( 9);
        return view('clients.destination', compact('title','tours'));
    }

    
}
