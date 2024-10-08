<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\CustomerInfo;
use Illuminate\Support\Facades\Log;

class CustomerInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $customerUsers = User::role('customer')->get();
      
        foreach ($customerUsers as $customer) {
            CustomerInfo::create([
                'user_id' => $customer->id,
                'admin_name' => $faker->name,
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
