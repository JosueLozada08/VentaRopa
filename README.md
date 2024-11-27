# Tienda Ropa - Sistema de Gestión y Predicción de Ventas

Este proyecto es una aplicación web diseñada para gestionar la venta de ropa, incluyendo administración de productos, categorías, órdenes y análisis de ventas. Además, incluye funcionalidades avanzadas como predicción de la categoría más vendida y un ranking de productos más populares.

## Características Principales

### Administración
- **Gestión de Productos**: Crear, editar, eliminar y listar productos con detalles como precio, stock y categoría.
- **Gestión de Categorías**: Crear, editar y listar categorías.
- **Gestión de Órdenes**: Ver y administrar las órdenes generadas por los clientes, incluyendo detalles de productos comprados.
- **Predicción de Ventas**: Predice la categoría con mayor probabilidad de ser la más vendida la próxima semana.
- **Ranking de Productos Más Vendidos**: Muestra un ranking de los productos más vendidos con sus cantidades.

### Cliente
- **Catálogo de Productos**: Los clientes pueden navegar y ver los productos disponibles junto con su stock.
- **Carrito de Compras**: Agregar productos al carrito y gestionar pedidos.
- **Confirmación de Órdenes**: Realizar compras directamente desde el carrito de compras.

## Requisitos Previos

Antes de ejecutar este proyecto, asegúrate de tener instalado lo siguiente:

- PHP >= 8.1
- Composer
- Laravel >= 11
- MySQL (o cualquier base de datos compatible con Laravel)
- Node.js y npm (opcional, para manejar frontend si se usa)
- Un servidor local como Laragon, XAMPP, o similar.
## Estructura del Proyecto

- **`app/Http/Controllers`**: Contiene los controladores para la administración (`Admin`) y clientes (`Customer`).
- **`app/Models`**: Define los modelos como `Product`, `Category`, `Order`, y `OrderProduct`.
- **`resources/views`**: Carpeta con las vistas de Blade para el frontend.
  - **`admin/`**: Vistas del dashboard de administración.
  - **`customer/`**: Vistas para el cliente.
- **`routes/web.php`**: Archivo de rutas que define todas las URLs disponibles en la aplicación.

## Funcionalidades Avanzadas

### Predicción de Categoría Más Vendida
La predicción utiliza los datos de ventas de los últimos 30 días para determinar la categoría con mayor probabilidad de ser la más vendida en la próxima semana. La probabilidad se muestra como un porcentaje en el dashboard de administración.

### Ranking de Productos Más Vendidos
Muestra los 5 productos más vendidos basándose en la cantidad total comprada en órdenes completadas.

## Uso del Sistema

### Administración
1. Inicia sesión como administrador usando un correo configurado como administrador.
2. Gestiona productos, categorías, y órdenes desde el dashboard.
3. Consulta el ranking de productos más vendidos y la predicción de ventas para tomar decisiones informadas.

### Cliente
1. Regístrate como cliente o usa una cuenta existente.
2. Explora los productos disponibles.
3. Agrega productos al carrito y realiza pedidos.



<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
