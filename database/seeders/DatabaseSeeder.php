<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Admin Account ---
        User::create([
            'name' => 'Admin Utama',
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // silakan ubah
        ]);

        // --- User Account ---
        User::create([
            'name' => 'User Biasa',
            'role' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'), // silakan ubah
        ]);
    }
}
