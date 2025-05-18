<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Pago;
use App\Models\Tamaño;
use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    /**
     * Crear un nuevo pedido a partir del carrito
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function crearPedido(Request $request)
{
    $validator = Validator::make($request->all(), [
        'direccion_entrega' => 'required|string',
        'metodo_pago' => 'required|in:efectivo,tarjeta',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422);
    }

    // Obtener items del carrito desde la base de datos
    $itemsCarrito = Carrito::with(['producto', 'tamaño'])
                        ->where('user_id', Auth::id())
                        ->get();

    // Verificar que el carrito tenga items
    if ($itemsCarrito->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'El carrito está vacío'
        ], 400);
    }

    // Verificar disponibilidad de productos y tamaños
    foreach ($itemsCarrito as $item) {
        if (!$item->producto || !$item->producto->disponible) {
            return response()->json([
                'status' => false,
                'message' => 'El producto ' . $item->producto->nombre . ' ya no está disponible'
            ], 400);
        }

        if (!$item->tamaño || !$item->tamaño->activo) {
            return response()->json([
                'status' => false,
                'message' => 'El tamaño ' . $item->tamaño->nombre . ' ya no está disponible'
            ], 400);
        }
    }

    try {
        DB::beginTransaction();

        // Calcular total
        $total = $itemsCarrito->sum('subtotal');

        // Crear el pedido
        $pedido = Pedido::create([
            'usuario_id' => Auth::id(),
            'estado' => Pedido::ESTADO_PENDIENTE,
            'total' => $total,
            'direccion_entrega' => $request->direccion_entrega,
        ]);

        // Crear detalles del pedido
        foreach ($itemsCarrito as $item) {
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item->producto_id,
                'tamaño_id' => $item->tamaño_id,
                'cantidad' => $item->cantidad,
                'precio' => $item->precio_unitario,
            ]);
        }

        // Crear registro de pago
        Pago::create([
            'pedido_id' => $pedido->id,
            'metodo' => $request->metodo_pago,
            'estado' => $request->metodo_pago == 'efectivo' 
                        ? Pago::ESTADO_PENDIENTE 
                        : Pago::ESTADO_PAGADO,
        ]);

        // Limpiar carrito después de crear el pedido
        Carrito::where('user_id', Auth::id())->delete();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Pedido creado exitosamente',
            'pedido' => [
                'id' => $pedido->id,
                'total' => $pedido->total,
                'estado' => $pedido->estado,
                'fecha' => $pedido->fecha_pedido,
            ]
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'status' => false,
            'message' => 'Error al crear el pedido',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Obtener listado de pedidos del usuario autenticado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function misPedidos()
    {
        $pedidos = Pedido::with(['detalles.producto', 'pago'])
            ->where('usuario_id', Auth::id())
            ->orderBy('fecha_pedido', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $pedidos
        ]);
    }

    /**
     * Obtener detalle de un pedido específico del usuario autenticado
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detallePedido($id)
    {
        $pedido = Pedido::with(['detalles.producto', 'detalles.tamaño', 'pago'])
            ->where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $pedido
        ]);
    }

    /**
     * [Admin/Repartidor] Listar todos los pedidos
     * Con filtros opcionales
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarPedidosAdmin(Request $request)
    {
        // Comprobar si el usuario es admin o repartidor
        $user = Auth::user();
        if (!in_array($user->rol, ['administrador', 'repartidor'])) {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para ver esta información'
            ], 403);
        }

        $query = Pedido::with(['usuario', 'detalles.producto', 'pago']);

        // Filtrar por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por fecha
        if ($request->has('fecha')) {
            $query->whereDate('fecha_pedido', $request->fecha);
        }

        // Si es repartidor, solo mostrar pedidos en camino asignados a él
        if ($user->rol === 'repartidor') {
            $query->where('estado', Pedido::ESTADO_EN_CAMINO);
            // Aquí se podría agregar una columna de repartidor_id para filtrar
        }

        $pedidos = $query->orderBy('fecha_pedido', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $pedidos
        ]);
    }

    /**
     * [Admin] Actualizar estado de un pedido
     *
     * @param Request $request 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstado(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (Auth::user()->rol !== 'administrador') {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:pendiente,preparación,en camino,entregado,cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $pedido = Pedido::findOrFail($id);
        $pedido->estado = $request->estado;
        $pedido->save();

        return response()->json([
            'status' => true,
            'message' => 'Estado del pedido actualizado correctamente',
            'data' => $pedido
        ]);
    }

    /**
     * [Repartidor] Obtener pedidos asignados al repartidor
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidosRepartidor()
    {
        // Verificar si el usuario es repartidor
        if (Auth::user()->rol !== 'repartidor') {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para acceder a esta información'
            ], 403);
        }

        // Obtener pedidos en estado "en camino"
        // Nota: En una implementación real, deberías filtrar por repartidor_id
        $pedidos = Pedido::with(['usuario', 'detalles.producto'])
            ->where('estado', Pedido::ESTADO_EN_CAMINO)
            ->orderBy('fecha_pedido', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $pedidos
        ]);
    }

    /**
     * [Repartidor] Marcar pedido como entregado
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function marcarEntregado($id)
    {
        // Verificar si el usuario es repartidor
        if (Auth::user()->rol !== 'repartidor') {
            return response()->json([
                'status' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        $pedido = Pedido::where('id', $id)
            ->where('estado', Pedido::ESTADO_EN_CAMINO)
            ->firstOrFail();

        $pedido->estado = Pedido::ESTADO_ENTREGADO;
        $pedido->save();

        // Actualizar estado del pago si fue en efectivo
        $pago = $pedido->pago;
        if ($pago && $pago->metodo === Pago::METODO_EFECTIVO) {
            $pago->estado = Pago::ESTADO_PAGADO;
            $pago->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Pedido marcado como entregado',
            'data' => $pedido
        ]);
    }
}