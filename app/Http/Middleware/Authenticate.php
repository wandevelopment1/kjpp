<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // kalau akses route investor
            // if ($request->is('investor') || $request->is('investor/*')) {
            //     return route('investor.login');
            // }

            // default untuk admin (user biasa)
            return route('admin.login');
        }
    }
}
