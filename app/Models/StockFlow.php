<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}

