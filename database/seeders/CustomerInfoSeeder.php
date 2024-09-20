<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\CustomerInfo;

class CustomerInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize Faker
        $faker = Faker::create();

        // Get all users with the role "customer"
        $customerUsers = User::role('customer')->pluck('id')->toArray();

        // Generate 20 records for customer_info table
        for ($i = 0; $i < 20; $i++) {
            CustomerInfo::create([
                'user_id' => $faker->randomElement($customerUsers), // Random user with "customer" role
                'admin_name' => $faker->name,
                'name' => $faker->name,
                'pec' => $faker->email,
                'rag_sociale' => $faker->sentence,
                'sdi' => $faker->sentence,
                'legal_address' => $faker->address,
                'iva' => $faker->sentence,
                'assigned_cars_count' => $faker->numberBetween(1, 10),
                'queued_cars_count' => $faker->numberBetween(0, 5),
                'finished_cars_count' => $faker->numberBetween(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
