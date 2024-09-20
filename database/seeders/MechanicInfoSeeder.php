<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\MechanicInfo;


class MechanicInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize Faker
        $faker = Faker::create();

        // Get all users with the role "mechanic"
        $mechanicUsers = User::role('mechanic')->pluck('id')->toArray();

        // Generate records for mechanic_infos table
        foreach ($mechanicUsers as $userId) {
            MechanicInfo::create([
                'user_id' => $userId,
                'surname' => $faker->lastName,
                'cdf' => $faker->sentence,
                'branch' => $faker->company,
                'plain_password' => $faker->password(6, 12),
                'repaired_count' => $faker->numberBetween(1, 100),
                'working_count' => $faker->numberBetween(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
