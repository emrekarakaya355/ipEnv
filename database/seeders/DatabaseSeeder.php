<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        (new RoleSeeder)->run();

    }
}
