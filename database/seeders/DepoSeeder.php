<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DepoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('depos')->insert([
            'user_id'      => 1,
            'type'   => 'freelance',
            'address'     => 'Jl Gebang Wetan 23 B',
            'city'     => 'Surabaya',
            'email'     => 'depo@admin.com',
            'phone'     => '082999121212',
        ]);
    }
}
