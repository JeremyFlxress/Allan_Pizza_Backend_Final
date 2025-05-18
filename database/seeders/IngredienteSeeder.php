<?php

namespace Database\Seeders;

use App\Models\Ingrediente;
use Illuminate\Database\Seeder;

class IngredienteSeeder extends Seeder
{
    public function run(): void
    {
        $ingredientes = [
            ['nombre' => 'Queso Mozzarella'],
            ['nombre' => 'Jamón'],
            ['nombre' => 'Peperoni'],
            ['nombre' => 'Salami'],
            ['nombre' => 'Carne de res'],
            ['nombre' => 'Carne de soya'],
            ['nombre' => 'Pollo'],
            ['nombre' => 'Piña'],
            ['nombre' => 'Champiñones'],
            ['nombre' => 'Chile verde'],
            ['nombre' => 'Cebolla morada'],
            ['nombre' => 'Tomate'],
            ['nombre' => 'Aceitunas'],
            ['nombre' => 'Jalapeños'],
            ['nombre' => 'Maíz'],
        ];

        foreach ($ingredientes as $ingrediente) {
            Ingrediente::create($ingrediente);
        }
    }
}