<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
        [
            'building' => 'Rektörlük',
            'unit' => 'Depo',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => 'Network',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => 'Personel',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => 'Öğrenci İşleri',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => '',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => 'Öğrenci İşleri',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => 'Öğrenci İşleri',
        ],
        [
            'building' => 'Rektörlük',
            'unit' => 'Öğrenci İşleri',
        ],

    ]);
    }
}
