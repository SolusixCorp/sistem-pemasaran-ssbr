<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StockFlow extends Model
{
    use HasFactory;
    protected $table = "stock_flow";

    public function depo()
    {
        return $this->hasOne(Depo::class, 'id', 'depo_id')->with('user');
    }

    public function product()
    {
        if (Auth::user()->role == 'ho') {
            return $this->hasOne(Product::class, 'id', 'product_id');
        } else {
            return $this->hasOne(ProductDepo::class, 'id', 'product_id')->with('product');
        }
    }

}

