<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryBarang extends Model
{
    use HasFactory;

    protected $table = "category_barang";
    protected $primaryKey = "category_id";


}
