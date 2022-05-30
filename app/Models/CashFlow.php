<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;protected $table = "cash_flow";

    public function depo()
    {
        return $this->hasOne(Depo::class, 'id', 'depo_id')->with('user');
    }
    
}
