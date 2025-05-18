<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::create([
            'nombre' => 'Admin',
            'email' => 'admin@allanpizza.com',
            'telefono' => '1234567890',
            'direccion' => 'Oficina central',
            'contraseña' => Hash::make('admin123'),
            'rol' => 'administrador',
        ]);

        // Repartidor
        User::create([
            'nombre' => 'Repartidor',
            'email' => 'repartidor@allanpizza.com',
            'telefono' => '0987654321',
            'direccion' => 'Sucursal Norte',
            'contraseña' => Hash::make('repartidor123'),
            'rol' => 'repartidor',
        ]);

        // Cliente de ejemplo
        User::create([
            'nombre' => 'Cliente Demo',
            'email' => 'cliente@ejemplo.com',
            'telefono' => '5555555555',
            'direccion' => 'Calle Principal #123',
            'contraseña' => Hash::make('cliente123'),
            'rol' => 'cliente',
        ]);
    }
}