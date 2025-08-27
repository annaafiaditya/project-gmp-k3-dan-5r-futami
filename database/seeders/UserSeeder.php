<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek dan buat user jika belum ada
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admingmp'),
                'role' => 'admin',
                'department' => 'QA',
                'is_verified' => true,
            ]);
        }

        if (!User::where('email', 'produksi@gmail.com')->exists()) {
            User::create([
                'name' => 'Produksi',
                'email' => 'produksi@gmail.com',
                'password' => Hash::make('produksi'),
                'role' => 'user',
                'department' => 'Produksi',
                'is_verified' => true,
            ]);
        }

        if (!User::where('email', 'warehouse@gmail.com')->exists()) {
            User::create([
                'name' => 'Warehouse',
                'email' => 'warehouse@gmail.com',
                'password' => Hash::make('warehouse'),
                'role' => 'user',
                'department' => 'Warehouse',
                'is_verified' => true,
            ]);
        }

        if (!User::where('email', 'engineering@gmail.com')->exists()) {
            User::create([
                'name' => 'Engineering',
                'email' => 'engineering@gmail.com',
                'password' => Hash::make('engineering'),
                'role' => 'user',
                'department' => 'Engineering',
                'is_verified' => true,
            ]);
        }

        if (!User::where('email', 'hrgmp@gmail.com')->exists()) {
            User::create([
                'name' => 'Human Resources',
                'email' => 'hrgmp@gmail.com',
                'password' => Hash::make('hrusergmp'),
                'role' => 'user',
                'department' => 'HR',
                'is_verified' => true,
            ]);
        }

        if (!User::where('email', 'qagmp@gmail.com')->exists()) {
            User::create([
                'name' => 'Quality Assurance',
                'email' => 'qagmp@gmail.com',
                'password' => Hash::make('qausergmp'),
                'role' => 'user',
                'department' => 'QA',
                'is_verified' => true,
            ]);
        }

        // Tambahkan user AG jika belum ada
        if (!User::where('email', 'aggmp@gmail.com')->exists()) {
            User::create([
                'name' => 'AG Department',
                'email' => 'aggmp@gmail.com',
                'password' => Hash::make('aguser'),
                'role' => 'user',
                'department' => 'AG',
                'is_verified' => true,
            ]);
        }

        // Tambahkan user IT jika belum ada
        if (!User::where('email', 'itgmp@gmail.com')->exists()) {
            User::create([
                'name' => 'IT Department',
                'email' => 'itgmp@gmail.com',
                'password' => Hash::make('ituser'),
                'role' => 'user',
                'department' => 'IT',
                'is_verified' => true,
            ]);
        }
    }
}
