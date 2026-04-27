<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'nim' => fake()->unique()->numerify('########'),
                'phone' => fake()->optional()->phoneNumber(),
                'faculty' => fake()->randomElement([
                    'Informatika',
                    'Rekayasa Perangkat Lunak',
                    'Teknologi Informasi',
                    'Sistem Informasi',
                    'Teknik Elektro',
                    'Teknik Telekomunikasi',
                    'Teknik Komputer',
                    'Teknik Industri',
                    'Teknik Logistik',
                    'Sains Data',
                    'Digital Bisnis'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
