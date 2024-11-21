<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginAdminController extends Controller
{
    public function index(){
        $title = 'Đăng nhập';

        return view('admin.login', compact('title'));
    }
}
