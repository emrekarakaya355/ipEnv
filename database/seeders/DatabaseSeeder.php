<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(5)->create();
        Location::factory(10)->create();
        DeviceType::factory(15)->create();
        Device::factory(1)->create();
        Device::factory(3)->create();
        Device::factory(5)->create();
        Device::factory(10)->create();
        DeviceInfo::factory(150)->create();

    }
}
