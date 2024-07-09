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
        return [
            'type' => $this->faker->randomElement(['switch', 'access point']),
            'brand' => $this->faker-> randomElement(array:['cisco','aruba','cem']),
            'model' => $this->faker->randomElement(array:['1851','315','205','512']),
        ];
    }
}
