<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Tamaño;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CarritoController extends Controller
{
    // Agregar producto al carrito
    public function agregarItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'tamaño_id' => 'required|exists:tamaños,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $producto = Producto::disponible()->find($request->producto_id);
        if (!$producto) {
            return response()->json([
                'status' => false,
                'message' => 'El producto no está disponible'
            ], 400);
        }

        $tamaño = Tamaño::where('activo', true)->find($request->tamaño_id);
        if (!$tamaño) {
            return response()->json([
                'status' => false,
                'message' => 'El tamaño seleccionado no está disponible'
            ], 400);
        }

        $precio = $producto->precio * $tamaño->multiplicador_precio;

        // Verificar si ya existe un ítem igual (mismo producto y tamaño) para sumarlo
        $item = Carrito::where('user_id', Auth::id())
            ->where('producto_id', $producto->id)
            ->where('tamaño_id', $tamaño->id)
            ->first();

        if ($item) {
            $item->cantidad += $request->cantidad;
            $item->subtotal = $item->precio_unitario * $item->cantidad;
            $item->save();
        } else {
            Carrito::create([
                'user_id' => Auth::id(),
                'producto_id' => $producto->id,
                'tamaño_id' => $tamaño->id,
                'cantidad' => $request->cantidad,
                'precio_unitario' => $precio,
                'subtotal' => $precio * $request->cantidad,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Producto agregado al carrito',
            'carrito' => $this->getCarritoData()
        ]);
    }

    // Actualizar cantidad
    public function actualizarItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cantidad' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $item = Carrito::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item no encontrado en el carrito'
            ], 404);
        }

        $item->cantidad = $request->cantidad;
        $item->subtotal = $item->precio_unitario * $item->cantidad;
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Carrito actualizado',
            'carrito' => $this->getCarritoData()
        ]);
    }

    // Eliminar ítem
    public function eliminarItem($id)
    {
        $item = Carrito::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item no encontrado en el carrito'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item eliminado del carrito',
            'carrito' => $this->getCarritoData()
        ]);
    }

    // Mostrar carrito
    public function mostrarCarrito()
    {
        return response()->json([
            'status' => true,
            'carrito' => $this->getCarritoData()
        ]);
    }

    // Vaciar carrito
    public function vaciarCarrito()
    {
        Carrito::where('user_id', Auth::id())->delete();

        return response()->json([
            'status' => true,
            'message' => 'Carrito vaciado correctamente',
            'carrito' => $this->getCarritoData()
        ]);
    }

    // Formato del carrito
    private function getCarritoData()
    {
        $items = Carrito::with('producto', 'tamaño')
            ->where('user_id', Auth::id())
            ->get();

        return [
            'items' => $items,
            'total' => $items->sum('subtotal'),
            'cantidad_items' => $items->count(),
        ];
    }
}
