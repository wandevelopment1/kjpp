<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);

        $available = Language::active()->pluck('code')->toArray();

        if (!in_array($locale, $available)) {
            $locale = config('app.fallback_locale');
        }

        // pakai Laravel helper
        app()->setLocale($locale);

        return $next($request);
    }
}
