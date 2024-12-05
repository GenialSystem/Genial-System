<?php

namespace Database\Seeders;

use App\Models\CarPart;
use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $customers = CustomerInfo::all()->pluck('id')->toArray();
       
        $mechanics = MechanicInfo::all()->pluck('id')->toArray();

        $carParts = CarPart::pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            // Insert order
            $orderId = DB::table('orders')->insertGetId([
                'state' => $faker->randomElement(['Riparata', 'Nuova', 'In lavorazione', 'Annullata']),
                'car_size' => $faker->randomElement(['Piccolo', 'Medio', 'Grande', 'Veicolo commerciale']),
                'plate' => strtoupper($faker->lexify('???-###')),
                'price' => $faker->randomFloat(2, 10, 500),
                'customer_id' => $faker->randomElement($customers),
                'notes' => $faker->sentence(),
                'earn_mechanic_percentage' => $faker->numberBetween(1, 90),
                'assembly_disassembly' => $faker->boolean(),
                'replacements' => $faker->sentence(),
                'brand' => 'Toyota',
                'aluminium' => $faker->boolean(),
                'damage_diameter' => '20 - 35mm',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach random mechanics to the order
            $randomMechanics = $faker->randomElements($mechanics, $faker->numberBetween(1, 3));
            foreach ($randomMechanics as $mechanicId) {
                DB::table('order_mechanic')->insert([
                    'order_id' => $orderId,
                    'mechanic_id' => $mechanicId,
                ]);
            }

            $randomCarParts = $faker->randomElements($carParts, $faker->numberBetween(1, 5)); // 1 to 5 parts per order
            foreach ($randomCarParts as $carPartId) {
                DB::table('order_car_part')->insert([
                    'order_id' => $orderId,
                    'car_part_id' => $carPartId,
                    'damage_count' => $faker->numberBetween(0, 10), // Random number of damages
                    'paint_prep' => $faker->boolean(),
                    'replacement' => $faker->boolean(),
                ]);
            }
        }
    }
}
