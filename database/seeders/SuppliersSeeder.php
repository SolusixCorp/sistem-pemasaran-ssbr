<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->insert([
            'supplier_name'      => 'Pemilik (Owner)',
            'supplier_address'   => 'Jl. Penanmbangan No.1 Desa Penambangan, Pajarakan, Kab. Probolinggo',
            'supplier_email'     => 'admin@kasir.app',
            'supplier_phone'     => '081000111222',
        ]);
    }
}
