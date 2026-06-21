<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Mail',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Kasir 01
        User::create([
            'name' => 'Upin',
            'email' => 'kasir01@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        // Kasir 02
        User::create([
            'name' => 'Ipin',
            'email' => 'kasir02@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        // Kasir 03
        User::create([
            'name' => 'Mei Mei',
            'email' => 'kasir03@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}