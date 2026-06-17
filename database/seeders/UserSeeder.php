<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@apotek.com',
            'password' => \Hash::make('password123'),
            'role' => 'admin_gudang',
        ]);

        \App\Models\User::create([
            'name' => 'Apoteker Utama',
            'email' => 'apoteker@apotek.com',
            'password' => \Hash::make('password123'),
            'role' => 'apoteker',
        ]);

        \App\Models\User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@apotek.com',
            'password' => \Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}
