<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PedidoPolicy
{
    public function view(User $user, Pedido $pedido)
    {
        return $user->id === $pedido->usuario_id || $user->rol === 'administrador'
            ? Response::allow()
            : Response::deny('No tienes permiso para ver este pedido.');
    }

    public function update(User $user, Pedido $pedido)
    {
        return $user->rol === 'administrador' || $user->rol === 'repartidor'
            ? Response::allow()
            : Response::deny('Solo administradores y repartidores pueden actualizar pedidos.');
    }

    public function delete(User $user, Pedido $pedido)
    {
        return $user->rol === 'administrador'
            ? Response::allow()
            : Response::deny('Solo administradores pueden cancelar pedidos.');
    }
}
