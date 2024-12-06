<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_admin';

    public function getAdmin(){
        return DB::table($this->table)->first();
    }

    public function updateAdmin($data){
        return DB::table($this->table)
        ->where('username', 'admin')
        ->update($data);
    }
}
