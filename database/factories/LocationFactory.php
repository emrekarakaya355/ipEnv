<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Location>
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
            'faculty' => fake()->randomElement(array:['Rektörlük','Fen','Diş','Cami','İlahiyat','Myo','rektör']).''.fake()->randomElement(array:['-','/','*','+','1','2','3'])
        ];
    }
}
