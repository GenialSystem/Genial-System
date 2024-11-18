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
        $faker = Faker::create();
        $mechanicUsersId = User::role('mechanic')->pluck('id')->toArray();

        foreach ($mechanicUsersId as $userId) {
            MechanicInfo::create([
                'user_id' => $userId,
                'branch' => $faker->company,
                'plain_password' => 'password',
                'repaired_count' => $faker->numberBetween(1, 100),
                'working_count' => $faker->numberBetween(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
