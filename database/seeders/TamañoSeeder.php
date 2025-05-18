<?php

namespace Database\Seeders;

use App\Models\Tamaño;
use Illuminate\Database\Seeder;

class TamañoSeeder extends Seeder
{
    public function run(): void
    {
        $tamaños = [
            [
                'nombre' => 'Personal',
                'multiplicador_precio' => 1.00,
            ],
            [
                'nombre' => 'Mediana',
                'multiplicador_precio' => 1.50,
            ],
            [
                'nombre' => 'Grande',
                'multiplicador_precio' => 2.00,
            ],
            [
                'nombre' => 'Familiar',
                'multiplicador_precio' => 2.50,
            ],
        ];

        foreach ($tamaños as $tamaño) {
            Tamaño::create($tamaño);
        }
    }
}