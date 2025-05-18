<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'categoria',
        'disponible',
    ];

    // Relaciones
    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'producto_ingrediente', 'producto_id', 'ingrediente_id');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'producto_id');
    }
    
    // Scope para productos disponibles
    public function scopeDisponible($query)
    {
        return $query->where('disponible', 1);
    }
    
    // Scope para filtrar por categoría
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}