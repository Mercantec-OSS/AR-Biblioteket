<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('test'), // Hash the password
            'department' => 'test', // Optional: Add a department if needed
            'loggedIn' => false, // Optional: Set loggedIn status
        ]);

        $this->call([
            EducationSeeder::class
        ]);
    }
}