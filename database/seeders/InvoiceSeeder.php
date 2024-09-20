<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::role(['customer', 'mechanic'])->pluck('id')->toArray();

        for ($i = 0; $i < 18; $i++) {
            Invoice::create([
                'is_closed' => rand(0, 1),
                'user_id' => $userIds[array_rand($userIds)],
                'price' => round(rand(100, 10000) / 100, 2),
                'iva' => round(rand(100, 10000) / 100, 2),
            ]);
        }
    }
}
