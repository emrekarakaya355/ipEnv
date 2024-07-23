<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeviceType>
 */
class DeviceTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['switch', 'access_point']);

        if ($type === 'switch') {
            $brands = ['Cisco', 'Seckin', 'TP-Link', 'Extreme', 'Netgear', 'Ruckus'];
            $models = ['Sg590', 'SY12', 'AD123', 'AB512'];

        } else {
            $brands = ['Cisco', 'Aruba', 'HP', 'TP-Link', 'Extreme', 'Netgear', 'Ruckus'];
            $models = ['1851', '315', '205', '512'];

        }

        return [
            'type' => $type,
            'brand' => $this->faker->randomElement($brands),
            'model' => $this->faker->randomElement($models).' '.$this->faker->randomElement(['1','2','3','4']),
        ];
    }
}
