<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Impor Schema Facade

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Nonaktifkan pengecekan foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel
        Category::truncate();

        // 3. Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();

        // Data kategori yang akan dimasukkan
        $categories = [
            ['name' => 'Food'],
            ['name' => 'Drink'],
        ];

        // Masukkan data ke dalam tabel categories
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}