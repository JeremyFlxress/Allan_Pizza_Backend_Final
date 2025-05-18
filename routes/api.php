<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // Carrito
    Route::post('/carrito', [CarritoController::class, 'agregarItem']);
    Route::get('/carrito', [CarritoController::class, 'mostrarCarrito']);
    Route::put('/carrito/{itemId}', [CarritoController::class, 'actualizarItem']);
    Route::delete('/carrito/{itemId}', [CarritoController::class, 'eliminarItem']);
    Route::delete('/carrito', [CarritoController::class, 'vaciarCarrito']);
    
    // Pedidos (clientes)
    Route::post('/pedidos', [PedidoController::class, 'crearPedido']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'detallePedido']);
    Route::get('/historial', [PedidoController::class, 'misPedidos']);
    
    // Pagos (clientes)
    Route::put('/pagos/{id}', [PagoController::class, 'actualizarEstadoPago']);
    Route::get('/pagos/{id}', [PagoController::class, 'detallePago']);
    
    // Rutas para administradores
    Route::middleware('role:administrador')->prefix('admin')->group(function () {
        // Productos (CRUD)
        Route::post('/productos', [ProductoController::class, 'store']);
        Route::put('/productos/{id}', [ProductoController::class, 'update']);
        Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
        
        // Pedidos
        Route::get('/pedidos', [PedidoController::class, 'listarPedidosAdmin']);
        Route::put('/pedidos/{id}', [PedidoController::class, 'actualizarEstado']);
        
        // Pagos
        Route::get('/pagos', [PagoController::class, 'listarPagos']);
    });
    
    // Rutas para repartidores
    Route::middleware('role:repartidor')->prefix('repartidor')->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'pedidosRepartidor']);
        Route::put('/pedidos/{id}/entregado', [PedidoController::class, 'marcarEntregado']);
    });
});