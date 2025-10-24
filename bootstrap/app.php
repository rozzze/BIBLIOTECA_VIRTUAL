<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware as MiddlewarePermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware as MiddlewareRoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware as MiddlewareRoleOrPermissionMiddleware;
// ⬇️ IMPORTA las clases de Spatie
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 🔐 Alias de middlewares de Spatie para usarlos en rutas:
        $middleware->alias([
            'role' => MiddlewareRoleMiddleware::class,
            'permission' => MiddlewarePermissionMiddleware::class,
            'role_or_permission' => MiddlewareRoleOrPermissionMiddleware::class,
        ]);

        // (opcional) aquí puedes agregar $middleware->web([...]) o $middleware->append(...)
        // si en algún momento necesitas globales.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
