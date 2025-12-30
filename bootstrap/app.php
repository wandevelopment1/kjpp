<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request; // ✅ pakai Symfony Request, bukan Illuminate

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware (contoh: spatie/permission)
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'set_locale' => \App\Http\Middleware\SetLocale::class,
        ]);

        // Redirect jika user belum login (guest)
        $middleware->redirectGuestsTo(function (Request $request) {
            return route('admin.login'); // default login
        });

        // Redirect jika user sudah login
        $middleware->redirectUsersTo(function (Request $request) {
            if (auth('admin')->check()) {
                return route('admin.dashboard.index');
            }

            return route('home'); // default jika ada role lain
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    
    ->create();
