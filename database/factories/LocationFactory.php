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
            'building' => fake()->randomElement(array:['Rektörlük','Fen','Diş','Cami','İlahiyat','Myo','rektör']).'',
            'unit' => fake()->randomElement(array:['öğrenci işleri','dekan','personel','güvenlik','depo'])
        ];
    }
}
