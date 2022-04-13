<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supply;

class Depo extends Model
{
    use HasFactory;

    protected $table = "suppliers";
    protected $primaryKey = 'supplier_id';

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
