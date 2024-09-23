<?php

namespace Database\Seeders;

use App\Models\CustomerInfo;
use App\Models\Estimate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstimatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = CustomerInfo::all();

        foreach ($customers as $customer) {
            Estimate::create([
                'customer_id' => $customer->id,
                'type' => $this->getRandomType(),
                'state' => $this->getRandomState(),
                'price' => rand(100, 10000) / 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get a random type for the estimate.
     */
    private function getRandomType()
    {
        $types = ['Preventivo combinato', 'Preventivo leva bolli', 'Carrozzeria'];
        return $types[array_rand($types)];
    }

    /**
     * Get a random state for the estimate.
     */
    private function getRandomState()
    {
        $states = ['Archiviato', 'Nuovo', 'Confermato', 'Poco interessati', 'Annullato'];
        return $states[array_rand($states)];
    }
}
