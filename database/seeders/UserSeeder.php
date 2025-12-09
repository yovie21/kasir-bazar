<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
        public function run(): void
        {
            DB::table('users')->insert([
        [
            'name' => 'Admin Bazar',
            'email' => 'admin@bazar.com',
            'password' => bcrypt('password'),
            'role' => 1, // admin
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Kasir Bazar',
            'email' => 'kasir@bazar.com',
            'password' => bcrypt('password'),
            'role' => 0, // kasir
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Spv Bazar',
            'email' => 'spv@bazar.com',
            'password' => bcrypt('password'),
            'role' => 0, // kasir
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    }
}
