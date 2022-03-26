<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'company_name'      => 'Kasir Penambangan',
            'company_address'   => 'Jl. Penanmbangan No.1 Desa Penambangan, Pajarakan, Kab. Probolinggo',
            'company_email'     => 'admin@kasir.app',
            'company_phone'     => '081000111222',
            'invoice_prefix'    => 'TRXAPP',
            'company_logo'      => '',
        ]);
    }
}
