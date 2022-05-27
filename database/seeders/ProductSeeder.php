<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name'             => 'Product 1',
                'category_id'      => 1,
                'description'      => 'Isi 12',
                'consument_price'  => 20000,
                'retail_price'     => 19000,
                'sub_whole_price'  => 18000,
                'wholesales_price' => 17000,
                'stock'            => 50,
                'status'           => "Aktif",
            ],[
                'name'             => 'Product 2',
                'category_id'      => 1,
                'description'      => 'Isi 12',
                'consument_price'  => 20000,
                'retail_price'     => 19000,
                'sub_whole_price'  => 18000,
                'wholesales_price' => 17000,
                'stock'            => 50,
                'status'           => "Aktif",
            ]
        ]);
    }
}
