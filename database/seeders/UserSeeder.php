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
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@apotek.com'],
            [
                'name' => 'Admin Gudang',
                'password' => \Hash::make('password123'),
                'role' => 'admin_gudang',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'apoteker@apotek.com'],
            [
                'name' => 'Apoteker Utama',
                'password' => \Hash::make('password123'),
                'role' => 'apoteker',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'kasir@apotek.com'],
            [
                'name' => 'Kasir 1',
                'password' => \Hash::make('password123'),
                'role' => 'kasir',
            ]
        );
    }
}
