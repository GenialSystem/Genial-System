<?php

namespace Database\Seeders;

use App\Models\CarPart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parts = ['Cofano', 'Parafango Ant Dx', 'Porta Ant Dx',
        'Porta Pos Dx',
        'Montante Dx',
        'Parafango Pos Dx',
        'Parafango Ant Sx',
        'Porta Ant Sx',
        'Porta Pos Sx',
        'Montante Sx',
        'Parafango Pos Sx',
        'Tetto',
        'Baule'
    ];
      foreach ($parts as $part) {
        CarPart::create(['name' => $part]);
      }
    }
}
