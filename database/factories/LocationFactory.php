<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'faculty' => fake()->randomElement(array:['Rektörlük','Fen','Diş','Cami','İlahiyat']),
            'block' => fake()->randomElement(array:['A','B','C','D','Z']),
            'floor' => fake()->randomElement(array:['Z','1','2','3','4']),
            'room_number' => fake()->numberBetween(0, 500),
        ];
    }
}
