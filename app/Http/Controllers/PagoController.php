<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    /**
     * Actualizar estado de pago (para pagos con tarjeta)
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstadoPago(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:pagado,fallido',
            'referencia' => 'nullable|string|max:100', // Referencia de pago opcional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar el pago relacionado con el pedido
        $pago = Pago::where('pedido_id', $id)->firstOrFail();
        
        // Verificar que sea pago con tarjeta
        if ($pago->metodo !== Pago::METODO_TARJETA) {
            return response()->json([
                'status' => false,
                'message' => 'Solo se pueden actualizar pagos con tarjeta'
            ], 400);
        }
        
        // Verificar que el pedido pertenezca al usuario (si no es admin)
        $pedido = $pago->pedido;
        if (Auth::user()->rol !== 'administrador' && $pedido->usuario_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para actualizar este pago'
            ], 403);
        }

        // Actualizar estado del pago
        $pago->estado = $request->estado;
        
        // Si se proporciona referencia, guardarla
        if ($request->has('referencia')) {
            $pago->referencia = $request->referencia;
        }
        
        $pago->save();

        // Si el pago falla, cancelar el pedido
        if ($request->estado === Pago::ESTADO_FALLIDO) {
            $pedido->estado = Pedido::ESTADO_CANCELADO;
            $pedido->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Estado del pago actualizado correctamente',
            'data' => $pago
        ]);
    }

    /**
     * Obtener detalles de un pago específico
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detallePago($id)
    {
        $pago = Pago::with('pedido')->findOrFail($id);
        
        // Verificar permisos (solo admin o el propio usuario pueden ver)
        if (Auth::user()->rol !== 'administrador' && $pago->pedido->usuario_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para ver este pago'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'data' => $pago
        ]);
    }

    /**
     * [Admin] Listar todos los pagos con filtros opcionales
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarPagos(Request $request)
    {
        // Verificar si el usuario es administrador
        if (Auth::user()->rol !== 'administrador') {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para ver esta información'
            ], 403);
        }

        $query = Pago::with('pedido.usuario');

        // Filtrar por método de pago
        if ($request->has('metodo')) {
            $query->where('metodo', $request->metodo);
        }

        // Filtrar por estado de pago
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por fecha
        if ($request->has('fecha')) {
            $query->whereDate('fecha_pago', $request->fecha);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $pagos
        ]);
    }
}