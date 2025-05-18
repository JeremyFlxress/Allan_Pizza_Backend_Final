<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Verificar si el usuario tiene alguno de los roles especificados
        if (!in_array($request->user()->rol, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para acceder a este recurso'
            ], 403);
        }

        return $next($request);
    }
}
