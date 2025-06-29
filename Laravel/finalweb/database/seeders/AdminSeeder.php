<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Feryl',
            'email' => 'feryl@admin.com',
            'password' => Hash::make('12345678'), // Ganti dengan password yang kuat dan aman!
        ]);
    }
}