<?php

namespace Database\Seeders;

use App\Models\pelanggans_model;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PelanggansSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');
        $count = 120;
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $createdAt = $now->copy()->subDays(random_int(0, 365))->setTime(random_int(8, 20), random_int(0, 59));
            pelanggans_model::create([
                'nama_pelanggan' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => 'pelanggan123',
                'tgl_lahir' => $faker->date(),
                'telepon' => $faker->numerify('08##########'),
                'alamat1' => $faker->streetAddress(),
                'alamat2' => $faker->city(),
                'alamat3' => $faker->state(),
                'kartu_id' => Arr::random(['KTP', 'SIM', 'PASPOR']),
                'foto' => 'https://img.daisyui.com/images/profile/demo/3@94.webp',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
