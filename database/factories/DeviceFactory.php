<?php

namespace Database\Factories;

use App\Models\DeviceType;
use App\Models\Location;
use App\Models\NetworkSwitch;
use App\Models\Device;
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
        $locationId = Location::inRandomOrder()->first()->id ?? null;

        $deviceType = DeviceType::inRandomOrder()->first();


        $parentSwitchId = NetworkSwitch::inRandomOrder()->first()?->id;
        $useParentSwitch = $this->faker->boolean(90);
        return [
            'type' => $deviceType->type,
            'location_id' => $locationId,
            'device_type_id' => $deviceType->id,
            'serial_number' => $this->faker->numberBetween(1000, 99999),
            'name' => $this->faker->word,
            'ip_address' => $this->faker->ipv4,
            'status' => $this->faker->boolean,
            'room_number' => fake()->numberBetween(0, 500),
            'block' => fake()->randomElement(array:['A','B','C','D','Z','E','F']),
            'floor' => fake()->randomDigit(),
            'parent_switch_id' => $useParentSwitch && $parentSwitchId ? $parentSwitchId : null,
        ];
    }
}
