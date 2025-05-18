<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    
    public $timestamps = false;
    
    protected $fillable = [
        'pedido_id',
        'metodo',
        'estado',
    ];

    protected $dates = [
        'fecha_pago',
    ];

    // Definir constantes para métodos de pago
    const METODO_EFECTIVO = 'efectivo';
    const METODO_TARJETA = 'tarjeta';

    // Definir constantes para estados de pago
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADO = 'pagado';
    const ESTADO_FALLIDO = 'fallido';

    // Relaciones
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}