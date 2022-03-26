<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\Supplier;

class Supply extends Model
{
    use HasFactory;

    protected $table = "supply";
    protected $primaryKey = "id";

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function barang()
    {
        return $this->hasOne(Barang::class, 'id', 'barang_id');
    }
}
