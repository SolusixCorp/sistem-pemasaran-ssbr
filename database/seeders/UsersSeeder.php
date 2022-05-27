<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        DB::table('users')->insert([
            [
                'name'      => 'Admin HO',
                'email'     => 'admin@ho.com',
                'role'      => 'ho',
                'password'  => Hash::make('admin123'),
            ],[
                'name'      => 'Admin Depo',
                'email'     => 'admin@depo.com',
                'role'      => 'depo',
                'password'  => Hash::make('admin123'),
            ]
        ]);
    }
}
