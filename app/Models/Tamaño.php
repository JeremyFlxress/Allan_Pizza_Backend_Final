<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamaño extends Model
{
    use HasFactory;

    protected $table = 'tamaños';
    
    protected $fillable = [
        'nombre',
        'factor_precio'
    ];

    // Relaciones
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'tamaño_id');
    }
}