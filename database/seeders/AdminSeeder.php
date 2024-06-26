<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('musers')->insert([
            'name' => "Super Admin",
            'username' => 'administrator',
            'nomer_telp' => '08123456789',
            'tempat_lahir' => 'semarang',
            'tanggal_lahir' => Carbon::create('2002', '5', '16'),
            'alamat' => 'semarang',
            'unid' => Str::uuid(),
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => 1
        ]);
    }
}
