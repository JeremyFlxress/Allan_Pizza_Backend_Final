# Allan_Pizza_Backend_Final
# Allan_Pizza_Backend_Final

# API Backend Laravel - Proyecto Perrones

Esta API está desarrollada en Laravel 10 y contiene los endpoints para gestionar usuarios, carrito, pedidos, administración y reparto.

---

## Requisitos Previos

- PHP 8.1 o superior  
- Composer  
- MySQL o MariaDB  
- Postman (recomendado para probar la API)  

---

## Instalación y Ejecución

1. Clonar el repositorio:

git clone https://github.com/JeremyFlxress/Allan_Pizza_Backend_Final.git //RECALCAR QUE EL PROYECTO ESTA EN LA RAMA MASTER, una disculpa
cd se van al proyecto
Instalar dependencias de PHP con Composer:

composer install

Ejecutar migraciones y seeders para crear tablas y datos iniciales:

php artisan migrate
php artisan migrate --seed

Iniciar servidor local:

php artisan serve 

La API estará disponible en: http://127.0.0.1:8000

Configuración de CORS
El manejo de CORS ya está configurado para permitir peticiones desde el frontend.
Puedes encontrar la configuración en:
config/cors.php
Si hay problemas de conexión o errores relacionados con CORS, por favor revisa este archivo y ajusta los orígenes permitidos según sea necesario.

Uso de la API (Endpoints principales)
aca les dejo ejemplos de como pueden probar la api en post man

Registro de usuario
POST /api/register
Ejemplo JSON:

{
  "nombre": "Usuario Prueba",
  "email": "usuario@prueba.com",
  "telefono": "1234567890",
  "direccion": "Calle Test #123",
  "contraseña": "password123",
  "contraseña_confirmation": "password123"
}
Login
POST /api/login
Ejemplo JSON:


{
  "email": "usuario@prueba.com",
  "password": "password123"
}
Carrito (requiere autenticación)
Agregar producto: POST /api/carrito


{
  "producto_id": 1,
  "tamaño_id": 2,
  "cantidad": 1
}
Actualizar ítem: PUT /api/carrito/{id}

{
  "cantidad": 2
}
Pedidos (requiere autenticación)
Crear pedido: POST /api/pedidos

{
  "productos": [
    { "id": 1, "cantidad": 2 },
    { "id": 3, "cantidad": 1 }
  ],
  "direccion_entrega": "Calle Entrega #456",
  "metodo_pago": "efectivo"
}
Administración (requiere cuenta admin)
Actualizar estado de pedido: PUT /api/admin/pedidos/{id}


{
  "estado": "preparación"
}
Actualizar estado de pago: PUT /api/pagos/{id}

{
  "estado": "pagado",
  "referencia": "REF-123456"
}
Crear producto: POST /api/admin/productos


{
  "nombre": "Pizza Especial",
  "descripcion": "Pizza con ingredientes premium",
  "precio": 12.99,
  "imagen": "pizza_especial.jpg",
  "categoria": "pizza",
  "disponible": true,
  "ingredientes": [1, 2, 3, 9]
}
Actualizar producto: PUT /api/admin/productos/{id}

{
  "nombre": "Pizza Super Especial",
  "precio": 14.99,
  "disponible": true
}
Repartidor (requiere cuenta repartidor)
Marcar pedido como entregado: PUT /api/repartidor/pedidos/{id}/entregado


{
  "comentario": "Entregado en la puerta principal"
}

Probar API con Postman
Usar el endpoint de registro (register) para crear un usuario.

Hacer login para obtener el token de autenticación (Bearer Token). y con eso hacen acciones diferentes

Incluyan una vez iniciada sesion el token que les da, para hacer peticiones de pedidios etc.

Usar cuerpo JSON en peticiones POST y PUT.

