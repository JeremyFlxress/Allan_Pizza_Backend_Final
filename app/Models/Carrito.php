<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'producto_id',
        'tamaño_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function tamaño()
    {
        return $this->belongsTo(Tamaño::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
