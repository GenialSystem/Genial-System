<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_order_id',
            'view_start_date',
            'view_work_deadline',
            'view_branch',
            'view_mechanic',
            'view_mechanic_earn',
            'view_car_data',
            'view_total_price',
            'view_iva',
            'view_technical_data',
            'view_total_stamps',
            'view_assembly',
            'view_aluminum',
            'view_stamps_diameter',
            'view_car_size',
            'view_spare_parts',
            'view_notes',
            'view_photos',
            'view_deassembly_photos',
            'view_photo_edits',
            'view_upload_photos',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
