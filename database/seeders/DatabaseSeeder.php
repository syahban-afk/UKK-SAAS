<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::firstOrCreate(
            ['email' => 'owner@gmail.com'],
            ['name' => 'Owner', 'password' => Hash::make('owner123'), 'level' => 'owner']
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin123'), 'level' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'kurir@gmail.com'],
            ['name' => 'Kurir', 'password' => Hash::make('kurir123'), 'level' => 'kurir']
        );

        $this->call([
            JenisPembayaransSeeder::class,
            DetailJenisPembayaransSeeder::class,
            PaketsSeeder::class,
            PelanggansSeeder::class,
            PemesanansSeeder::class,
            PengirimansSeeder::class,
        ]);
    }
}
