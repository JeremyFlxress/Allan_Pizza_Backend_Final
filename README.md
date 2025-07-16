# 🍕 Allan_Pizza_Backend_Final - API Laravel
# PROYECTO FINAL EN LA RAMA "MASTER"

**API desarrollada en Laravel 10** para el sistema de gestión de pizzas **"Proyecto Perrones"**.

## 📦 Funcionalidades

Incluye endpoints para:

- Gestión de usuarios
- Carrito de compras
- Pedidos
- Administración
- Sistema de reparto

---

## ⚙️ Requisitos Previos

- PHP 8.1 o superior
- Composer
- MySQL
- Postman (recomendado para pruebas)

---

## 🚀 Instalación

1. **Clonar el repositorio**


git clone https://github.com/JeremyFlxress/Allan_Pizza_Backend_Final.git



📌 Nota: El proyecto se encuentra en la rama master.



Navegar al directorio del proyecto
cd Allan_Pizza_Backend_Final
composer install


#Configurar base de datos
php artisan migrate
php artisan migrate --seed

##Iniciar el servidor 
php artisan serve

La API estará disponible en: http://127.0.0.1:8000

🌐 Configuración de CORS
El manejo de CORS está configurado en config/cors.php.

Si encuentras problemas de conexión:

Revisa este archivo

Ajusta los orígenes permitidos según sea necesario



📡 Endpoints Principales
🔐 Autenticación
📋 Registro de usuario
Método: POST

Ruta: /api/register

ejemplo:
{
  "nombre": "Usuario Prueba",
  "email": "usuario@prueba.com",
  "telefono": "1234567890",
  "direccion": "Calle Test #123",
  "contraseña": "password123",
  "contraseña_confirmation": "password123"
}

🔑 Login
Método: POST

Ruta: /api/login
{
  "email": "usuario@prueba.com",
  "password": "password123"
}


🛒 Carrito (requiere autenticación)
➕ Agregar producto
Método: POST

Ruta: /api/carrito

{
  "producto_id": 1,
  "tamaño_id": 2,
  "cantidad": 1
}
✏️ Actualizar ítem
Método: PUT

Ruta: /api/carrito/{id}
{
  "cantidad": 2
}

📦 Pedidos (requiere autenticación)
📝 Crear pedido
Método: POST

Ruta: /api/pedidos

{
  "productos": [
    { "id": 1, "cantidad": 2 },
    { "id": 3, "cantidad": 1 }
  ],
  "direccion_entrega": "Calle Entrega #456",
  "metodo_pago": "efectivo"
}


### 🛠️ Administración (requiere cuenta admin)

## 🔄 Actualizar estado de pedido

- **Método:** `PUT`  
- **Ruta:** `/api/admin/pedidos/{id}`

**Ejemplo JSON:**

{
  "estado": "preparación"
}


💰 Actualizar estado de pago
Método: PUT

Ruta: /api/pagos/{id}

{
  "estado": "pagado",
  "referencia": "REF-123456"
}
🍕 Crear producto
Método: POST

Ruta: /api/admin/productos

{
  "nombre": "Pizza Especial",
  "descripcion": "Pizza con ingredientes premium",
  "precio": 12.99,
  "imagen": "pizza_especial.jpg",
  "categoria": "pizza",
  "disponible": true,
  "ingredientes": [1, 2, 3, 9]
}
📝 Actualizar producto
Método: PUT

Ruta: /api/admin/productos/{id}
{
  "nombre": "Pizza Super Especial",
  "precio": 14.99,
  "disponible": true
}



🛵 Repartidor (requiere cuenta repartidor)
✅ Marcar pedido como entregado
Método: PUT

Ruta: /api/repartidor/pedidos/{id}/entregado

{
  "comentario": "Entregado en la puerta principal"
}
