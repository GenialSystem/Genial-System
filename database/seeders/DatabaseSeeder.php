<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'mechanic', 'customer'];

        for ($i = 0; $i < 30; $i++) {
            $user = User::create([
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign a random role
            $randomRole = $roles[array_rand($roles)];
            $user->assignRole($randomRole);
        }
    }
}
