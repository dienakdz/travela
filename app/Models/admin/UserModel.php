<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_users';

    public function getAllUsers()
    {
        return DB::table($this->table)->get();
    }

    public function updateActive($id)
    {
        return DB::table($this->table)
            ->where('userId', $id) 
            ->update(['isActive' => 'y']); 
    }

    public function changeStatus($id, $data){
        return DB::table($this->table)
            ->where('userId', $id) 
            ->update($data); 
    }


}
