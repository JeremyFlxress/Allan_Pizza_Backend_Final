<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    public $timestamps = false;

    protected $casts = [
        'fecha_registro' => 'datetime'
        ];
        
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        'contraseña',
        'rol',
    ];

    protected $hidden = [
        'contraseña',
    ];

    // Modificamos el nombre del campo de password para que coincida con la BD
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Relaciones
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }
}