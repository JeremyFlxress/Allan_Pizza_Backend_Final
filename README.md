Allan_Pizza_Backend_Final - API Laravel
Descripción
API desarrollada en Laravel 10 para el sistema de gestión de pizzas "Proyecto Perrones". Contiene endpoints para:

Gestión de usuarios

Carrito de compras

Pedidos

Administración

Sistema de reparto

Requisitos Previos
PHP 8.1 o superior

Composer

MySQL

Postman (recomendado para pruebas)

Instalación
Clonar el repositorio:

bash
git clone https://github.com/JeremyFlxress/Allan_Pizza_Backend_Final.git
Nota: El proyecto está en la rama master.

Navegar al directorio del proyecto:

bash
cd Allan_Pizza_Backend_Final
Instalar dependencias:

bash
composer install
Configurar base de datos:

bash
php artisan migrate
php artisan migrate --seed
Iniciar servidor:

bash
php artisan serve
La API estará disponible en: http://127.0.0.1:8000

Configuración de CORS
El manejo de CORS está configurado en config/cors.php.

Si encuentras problemas de conexión:

Revisa este archivo

Ajusta los orígenes permitidos según sea necesario

Endpoints Principales
Autenticación
Registro de usuario

Método: POST

Ruta: /api/register

Ejemplo:

json
{
  "nombre": "Usuario Prueba",
  "email": "usuario@prueba.com",
  "telefono": "1234567890",
  "direccion": "Calle Test #123",
  "contraseña": "password123",
  "contraseña_confirmation": "password123"
}
Login

Método: POST

Ruta: /api/login

Ejemplo:

json
{
  "email": "usuario@prueba.com",
  "password": "password123"
}
Carrito (requiere autenticación)
Agregar producto

Método: POST

Ruta: /api/carrito

Ejemplo:

json
{
  "producto_id": 1,
  "tamaño_id": 2,
  "cantidad": 1
}
Actualizar ítem

Método: PUT

Ruta: /api/carrito/{id}

Ejemplo:

json
{
  "cantidad": 2
}
Pedidos (requiere autenticación)
Crear pedido

Método: POST

Ruta: /api/pedidos

Ejemplo:

json
{
  "productos": [
    { "id": 1, "cantidad": 2 },
    { "id": 3, "cantidad": 1 }
  ],
  "direccion_entrega": "Calle Entrega #456",
  "metodo_pago": "efectivo"
}
Administración (requiere cuenta admin)
Actualizar estado de pedido

Método: PUT

Ruta: /api/admin/pedidos/{id}
