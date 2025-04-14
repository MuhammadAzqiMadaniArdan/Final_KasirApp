<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "role" => "Admin",
            "password" => Hash::make('1234'),
        ]);
        
        User::create([
            "name" => "Employee",
            "email" => "pet@gmail.com",
            "role" => "Employee",
            "password" => Hash::make('1234'),
        ]);
    }
}
