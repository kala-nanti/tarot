<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil CategorySeeder yang baru kita buat
        $this->call([
            CategorySeeder::class,
            // Anda bisa memanggil seeder lain di sini nanti
        ]);
    }
}