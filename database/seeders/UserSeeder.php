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
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'level' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Owner
        User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('owner123'),
            'level' => 'owner',
            'email_verified_at' => now(),
        ]);

        // Kurir
        User::create([
            'name' => 'Kurir',
            'email' => 'kurir@gmail.com',
            'password' => Hash::make(value: 'kurir123'),
            'level' => 'kurir',
            'email_verified_at' => now(),
        ]);
    }
}
