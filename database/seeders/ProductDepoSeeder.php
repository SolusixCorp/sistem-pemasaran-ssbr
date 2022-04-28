<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductDepoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products_depo')->insert([
            'depo_id' => 1,
            'product_id' => 1,
            'stock' => 100,
            'depo_price' => 20000,
            'status' => "Aktif",
        ]);
    }
}
