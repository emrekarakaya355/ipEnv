<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Location;
use App\Models\NetworkSwitch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeviceInfo>
 */
class DeviceInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locationId = Location::inRandomOrder()->first()->id ?? null;

        return [
            'device_id' => function () {
               return Device::all()->random();
            },
            'location_id' => $locationId,
            'ip_address' => $this->faker->ipv4,
            'description' => fake()->word,
            'room_number' => fake()->numberBetween(0, 500),
            'block' => fake()->randomElement(array:['A','B','C','D','Z','E','F']),
            'update_reason' =>fake()->word,
            'floor' => fake()->randomDigit(),
        ];
    }
}
