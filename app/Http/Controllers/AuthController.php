<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario cliente
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:usuarios',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string',
            'contraseña' => 'required|string|min:6|confirmed',
             'rol' => ['required', Rule::in(['cliente', 'administrador', 'repartidor'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'contraseña' => Hash::make($request->contraseña), // Por defecto, todos los registros son clientes
            'rol' => $request->rol

        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }

    /**
     * Iniciar sesión y generar token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // encontrar usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->contraseña)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // esto crea token de autenticación
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Inicio de sesión exitoso',
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'email' => $user->email,
                'rol' => $user->rol,
            ],
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * cerrar sesión (revocar token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // revocar todos los tokens del usuario actual
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * obtener información del usuario autenticado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    }
}