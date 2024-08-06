<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_types')->insert([
            [
                'type' => 'switch',
                'brand' => 'BrandA',
                'model' => 'ModelX',
            ],
            [
                'type' => 'access_point',
                'brand' => 'BrandB',
                'model' => 'ModelY',
            ],
        ]);
    }
}
