<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'ingredientes';
    
    protected $fillable = [
        'nombre',
        'disponible',
    ];

    // Relaciones
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_ingrediente', 'ingrediente_id', 'producto_id');
    }
    
    // Scope para ingredientes disponibles
    public function scopeDisponible($query)
    {
        return $query->where('disponible', 1);
    }
}