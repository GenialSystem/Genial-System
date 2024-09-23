<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'mechanic', 'customer'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'city' => fake()->city,
                'cellphone' => fake()->phoneNumber,
                'cap' => fake()->numberBetween(9000, 10000),
                'address' => fake()->streetAddress,
                'province' => fake()->citySuffix,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign a random role from the roles array
            $randomRole = $roles[array_rand($roles)];
            $user->assignRole($randomRole);
        }
    }
}
