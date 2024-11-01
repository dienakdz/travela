<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Checkout extends Model
{
    use HasFactory;

    protected $table = 'tbl_checkout';

    public function createCheckout($data)
    {
        // Chèn dữ liệu và trả về ID của bản ghi vừa tạo
        return DB::table($this->table)->insertGetId($data);
    }
}
