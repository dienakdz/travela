<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ToursModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_tours';

    public function createTours($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }
}
