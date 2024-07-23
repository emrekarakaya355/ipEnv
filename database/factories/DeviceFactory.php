<?php

namespace Database\Factories;

use App\DeviceStatus;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\NetworkSwitch;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        $deviceType = DeviceType::inRandomOrder()->first();
        return [
            'type' => $deviceType->type,
            'device_type_id' => $deviceType->id,
            'serial_number' => $this->faker->numberBetween(1000, 99999),
            'registry_number' => $this->faker->numberBetween(1000, 99999),
            'status' => $this->faker->randomElement(DeviceStatus::toArray()),
            'device_name' => $this->faker->word,
            'parent_device_id' => function () {
                return NetworkSwitch::exists() ? NetworkSwitch::all()->random()->id : null;
            },
            'created_by'=> function () {
                return User::all()->random()->id;
            }
        ];

    }
}
