<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workstation;
use App\Models\CustomerInfo; // Add this import
use App\Models\MechanicInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class WorkstationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize Faker
        $faker = Faker::create();

        // Fetch customer_infos IDs, not User IDs
        $customerInfos = CustomerInfo::pluck('id')->toArray();

        // Create 20 fake workstation records
        for ($i = 0; $i < 20; $i++) {
            Workstation::create([
                'customer_id' => $faker->randomElement($customerInfos), // Use CustomerInfo IDs
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Fetch mechanic user IDs
        $mechanicUsers = MechanicInfo::pluck('id')->toArray();

        // Get all workstation IDs
        $workstations = Workstation::pluck('id')->toArray();

        // Assign each mechanic to a random number of workstations
        foreach ($mechanicUsers as $mechanicId) {
            // Randomly assign between 1 and 5 workstations to each mechanic
            $assignedWorkstations = $faker->randomElements($workstations, rand(1, 5));

            foreach ($assignedWorkstations as $workstationId) {
                // Insert into the pivot table
                DB::table('mechanic_workstation')->insert([
                    'mechanic_id' => $mechanicId,
                    'workstation_id' => $workstationId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
