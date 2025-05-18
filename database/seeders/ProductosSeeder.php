<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Pizza 4, suprema con orilla de queso',
                'descripcion' => '¡pizza suprema, 6 ingredientes más orilla de queso!',
                'precio' => 20.00,
                'imagen' => 'pizza_4suprema.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'Pizza de 12 porciones',
                'descripcion' => 'Rica pizza de peperoni carne y champiñones',
                'precio' => 10.00,
                'imagen' => 'pizza_12.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'Pizza de 8 porciones',
                'descripcion' => 'Un ingrediente a escoger peperoni, jamón o queso',
                'precio' => 5.00,
                'imagen' => 'pizza_8.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'Pizza personal',
                'descripcion' => '1 o 2 ingredientes elige entre los ingredientes de jamón, peperoni, salami y vegetales',
                'precio' => 2.99,
                'imagen' => 'pizza_personal.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'Pizza suprema',
                'descripcion' => '6 ingredientes: jamón, peperoni, carne, chile verde, cebolla morada y champiñones',
                'precio' => 8.99,
                'imagen' => 'pizza_suprema.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'Pizza de carne de soya',
                'descripcion' => 'Disfruta de esta exquisita pizza de carne de soya',
                'precio' => 8.99,
                'imagen' => 'pizza_carne.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'pizza de 2 especialidades',
                'descripcion' => 'pizza dividida por 2 ingredientes',
                'precio' => 8.99,
                'imagen' => 'pizza_2.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'pizza clasica de vegetales',
                'descripcion' => 'chile verde y cebolla',
                'precio' => 6.99,
                'imagen' => 'pizza_vetales.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'pizza vegetariana',
                'descripcion' => 'Una opción mas saludable con ingredientes como: champiñones, chile verde, rodajas de tomate y cebolla morada',
                'precio' => 7.99,
                'imagen' => 'pizza_vegetariana.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'pizza Ranchera',
                'descripcion' => 'Prueba sabores exoticos con nuestra pizza ranchera',
                'precio' => 11.00,
                'imagen' => 'pizza_ranchera.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'pizza Hawaiana',
                'descripcion' => 'piña, cebolla morada, chile verde, jamón y peperoni',
                'precio' => 7.99,
                'imagen' => 'pizza_hawaiana.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'pizza 4 estaciones',
                'descripcion' => 'Puedes escoger las 4 estaciones, tu decides los ingredientes',
                'precio' => 16.00,
                'imagen' => 'pizza_4estaciones.jpg',
                'categoria' => 'pizza',
                'disponible' => 1
            ],
            [
                'nombre' => 'Coca-Cola',
                'descripcion' => 'Coca cola de 1.5 litros',
                'precio' => 1.50,
                'imagen' => 'coca_cola.jpg',
                'categoria' => 'bebida',
                'disponible' => 1
            ],
            [
                'nombre' => 'Frappuccino',
                'descripcion' => 'bebedida de café congelado',
                'precio' => 1.50,
                'imagen' => 'frappuccino.jpg',
                'categoria' => 'bebida',
                'disponible' => 1
            ],
            [
                'nombre' => 'Frozen',
                'descripcion' => 'Frozen de galleta',
                'precio' => 1.50,
                'imagen' => 'frozen.jpg',
                'categoria' => 'bebida',
                'disponible' => 1
            ],
            [
                'nombre' => 'Frozen',
                'descripcion' => 'Frozen frutos del bosque',
                'precio' => 2.50,
                'imagen' => 'frozen_frutos.jpg',
                'categoria' => 'bebida',
                'disponible' => 1
            ],
            [
                'nombre' => 'Refresco',
                'descripcion' => 'Refresco natural, diferentes sabores',
                'precio' => 2.50,
                'imagen' => 'refresco.jpg',
                'categoria' => 'bebida',
                'disponible' => 1
            ],
            [
                'nombre' => 'Pan de Ajo',
                'descripcion' => '5 rebanadas de pan con crema de ajo y queso mozzarella',
                'precio' => 3.50,
                'imagen' => 'pan_ajo.jpg',
                'categoria' => 'acompañamiento',
                'disponible' => 1
            ],
            [
                'nombre' => 'Flauta',
                'descripcion' => 'Una flauta gigante con queso, pollo y champiñones',
                'precio' => 3.99,
                'imagen' => 'flauta.jpg',
                'categoria' => 'acompañamiento',
                'disponible' => 1
            ],
        ];

        DB::table('productos')->insert($productos);
    }
}