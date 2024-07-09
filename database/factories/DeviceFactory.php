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

        $deviceTypeId = DeviceType::inRandomOrder()->first()->id;


        $parentSwitchId = NetworkSwitch::inRandomOrder()->first()?->id;
        $useParentSwitch = $this->faker->boolean(90);

        return [
            'location_id' => $locationId,
            'device_type_id' => $deviceTypeId,
            'serial_number' => $this->faker->numberBetween(1000, 99999),
            'name' => $this->faker->word,
            'ip_address' => $this->faker->ipv4,
            'status' => $this->faker->boolean,
            'parent_switch_id' => $useParentSwitch && $parentSwitchId ? $parentSwitchId : null,
        ];
    }
}
