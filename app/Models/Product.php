<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\OrderItem;

class Product extends Model
{
    use HasFactory;
    protected $table = 'barangs';
    protected $primaryKey = 'barang_id';

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function order_items()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
