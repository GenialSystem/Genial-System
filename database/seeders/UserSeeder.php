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
        // Define roles
        $roles = ['admin', 'mechanic', 'customer'];

        // Create roles
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create 1 admin
        $admin = User::create([
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => 'admin@example.com',
            'password' => 'password',
            'cdf' => fake()->sentence,
            'city' => fake()->city,
            'cellphone' => fake()->phoneNumber,
            'cap' => fake()->numberBetween(9000, 10000),
            'address' => fake()->streetAddress,
            'province' => fake()->citySuffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin->assignRole('admin');  // Assign admin role

        // Create 15 mechanics
        for ($i = 0; $i < 15; $i++) {
            $mechanic = User::create([
                'name' => fake()->firstName(),
                'surname' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => 'password',
                'cdf' => fake()->sentence,
                'city' => fake()->city,
                'cellphone' => fake()->phoneNumber,
                'cap' => fake()->numberBetween(9000, 10000),
                'address' => fake()->streetAddress,
                'province' => fake()->citySuffix,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $mechanic->assignRole('mechanic');  // Assign mechanic role
        }

        // Create 15 customers
        for ($i = 0; $i < 15; $i++) {
            $customer = User::create([
                'name' => fake()->firstName(),
                'surname' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => 'password',
                'cdf' => fake()->sentence,
                'city' => fake()->city,
                'cellphone' => fake()->phoneNumber,
                'cap' => fake()->numberBetween(9000, 10000),
                'address' => fake()->streetAddress,
                'province' => fake()->citySuffix,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $customer->assignRole('customer');  // Assign customer role
        }
    }

}
