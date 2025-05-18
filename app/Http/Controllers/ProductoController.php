<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Obtener listado de productos disponibles
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Producto::disponible();
        
        // Filtrar por categoría si se proporciona
        if ($request->has('categoria')) {
            $query->categoria($request->categoria);
        }
        
        $productos = $query->get();
        
        return response()->json([
            'status' => true,
            'data' => $productos
        ]);
    }

    /**
     * Obtener detalle de un producto específico
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $producto = Producto::with('ingredientes')->findOrFail($id);
        
        return response()->json([
            'status' => true,
            'data' => $producto
        ]);
    }

    /**
     * [Admin] Crear un nuevo producto
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string|max:255',
            'categoria' => 'required|in:pizza,bebida,acompañamiento',
            'disponible' => 'boolean',
            'ingredientes' => 'nullable|array',
            'ingredientes.*' => 'exists:ingredientes,id',
        ]);

        $producto = Producto::create($request->all());
        
        // Sincronizar ingredientes si se proporcionan
        if ($request->has('ingredientes')) {
            $producto->ingredientes()->sync($request->ingredientes);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Producto creado exitosamente',
            'data' => $producto
        ], 201);
    }

    /**
     * [Admin] Actualizar un producto existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'numeric|min:0',
            'imagen' => 'nullable|string|max:255',
            'categoria' => 'in:pizza,bebida,acompañamiento',
            'disponible' => 'boolean',
            'ingredientes' => 'nullable|array',
            'ingredientes.*' => 'exists:ingredientes,id',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        
        // Sincronizar ingredientes si se proporcionan
        if ($request->has('ingredientes')) {
            $producto->ingredientes()->sync($request->ingredientes);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Producto actualizado exitosamente',
            'data' => $producto
        ]);
    }

    /**
     * [Admin] Eliminar un producto
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Producto eliminado exitosamente'
        ]);
    }
}