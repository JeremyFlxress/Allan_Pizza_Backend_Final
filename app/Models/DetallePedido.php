<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedido';
    
    public $timestamps = false;
    
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'tamaño_id',
        'cantidad',
        'precio',
    ];

    // Relaciones
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    
    public function tamaño()
    {
        return $this->belongsTo(Tamaño::class, 'tamaño_id');
    }
}