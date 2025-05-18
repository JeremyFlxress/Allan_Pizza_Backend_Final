<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'direccion_entrega' => 'required_if:metodo_entrega,envio|string|max:255',
            'metodo_pago' => 'required|in:efectivo,tarjeta'
        ];
    }

    public function messages()
    {
        return [
            'productos.required' => 'Debe agregar al menos un producto al pedido.',
            'productos.*.id.exists' => 'Uno de los productos seleccionados no existe.',
            'direccion_entrega.required_if' => 'La dirección es requerida para envíos.'
        ];
    }
}