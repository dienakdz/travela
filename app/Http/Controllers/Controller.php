<?php

namespace App\Http\Controllers;

use App\Models\clients\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    protected function getUserId()
    {
        if (!session()->has('userId')) {
            $username = session()->get('username');
            if ($username) {
                $userId = $this->user->getUserId($username);
                session()->put('userId', $userId);
            }
        }
        return session()->get('userId');
    }
}
