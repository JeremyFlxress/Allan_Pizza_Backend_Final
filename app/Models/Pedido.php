<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    
    public $timestamps = false;
    
    protected $fillable = [
        'usuario_id',
        'estado',
        'total',
        'direccion_entrega',
    ];

    protected $dates = [
        'fecha_pedido',
    ];

    // Definir los estados posibles para un pedido
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PREPARACION = 'preparación';
    const ESTADO_EN_CAMINO = 'en camino';
    const ESTADO_ENTREGADO = 'entregado';
    const ESTADO_CANCELADO = 'cancelado';

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'pedido_id');
    }
    
    // Scope para filtrar por estado
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
    
    // Scope para filtrar por usuario
    public function scopeUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }
}