<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## CINE: configuración local

La aplicación usa MySQL por defecto. Configura en `.env` la conexión `DB_CONNECTION=mysql`, el nombre de la base de datos y las credenciales de tu instalación local antes de ejecutar migraciones. Las migraciones de departamentos, ciudades, sucursales y trabajadores respetan las tablas y columnas existentes y agregan los datos de contacto faltantes sin borrar registros.

La administración incluye CRUD de películas, funciones, salas, departamentos, ciudades, sucursales y trabajadores. El dashboard muestra conteos y registros recientes desde la base de datos; las funciones validan que no se crucen horarios activos de una misma sala, y las salas relacionadas no se pueden borrar. La migración `2026_10_30_200000_add_admin_fields_to_cine_catalogs.php` agrega de forma aditiva el tipo de documento, fecha de contratación y estado de trabajadores, además del correo y estado de sucursales. No recrea tablas; su reversión no elimina esas columnas para evitar pérdida de datos.

La portada organiza el catálogo por los campos disponibles en `peliculas`. El esquema actual no almacena puntuaciones, tráileres ni reparto, por lo que la interfaz no inventa esos datos ni presenta controles de reproducción que no puedan funcionar.

Comandos seguros para inicializar o actualizar el esquema y cargar los datos de prueba (PHP 8.3 o posterior):

```bash
php artisan migrate
php artisan db:seed
```

Los seeders del catálogo usan `firstOrCreate` para permitir ejecuciones repetidas. No ejecutes `migrate:fresh` ni `db:wipe` sobre una base de datos con datos que deban conservarse.

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

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
