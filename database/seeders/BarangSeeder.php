<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('barangs')->insert([
            'name' => 'Test Barang',
            'category_id' => 1,
            'supplier_id' => 1,
            'merk' => "Test Merk",
            'buying_price' => 40000,
            'selling_price' => 50000,
            'stock' => 50,
            'status' => "Aktif",
        ]);
    }
}
