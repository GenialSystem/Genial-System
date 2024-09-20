<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();  // Start the transaction

        try {
            $this->call(UserSeeder::class);
            $this->call(PermissionsSeeder::class);
            $this->call(CarPartSeeder::class);
            $this->call(MechanicInfoSeeder::class);
            $this->call(CustomerInfoSeeder::class);
            $this->call(OrdersTableSeeder::class);
            $this->call(WorkstationSeeder::class);
            $this->call(EstimatesTableSeeder::class);
            $this->call(InvoiceSeeder::class);

            DB::commit();  // Commit the transaction if all seeders pass
        } catch (Throwable $e) {
            DB::rollBack();  // Rollback the transaction if any seeder fails
            $this->command->error('Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
