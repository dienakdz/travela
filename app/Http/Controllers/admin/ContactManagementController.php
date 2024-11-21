<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactManagementController extends Controller
{
    public function index(){
        $title = 'Liên hệ';

        return view('admin.contact', compact('title'));
    }
}
