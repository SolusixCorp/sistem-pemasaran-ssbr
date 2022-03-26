<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_barang')->insert([
            'category_name' => 'Bahan Pokok',
            'status'        => 'Aktif',
        ]);
    }
}
