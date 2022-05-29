<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Depo;

class Depo extends Model
{
    use HasFactory;

    protected $table = "depos";
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
