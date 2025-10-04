<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromUrl
{
    public function handle(Request $request, Closure $next)
    {
        $supported = config('locales.supported', ['et','ru','en','uk']);
        $default   = config('locales.default', 'et');

        $routeLocale = $request->route('locale'); // null для безпрефиксных

        $locale = $routeLocale ?: $default;
        if (!in_array($locale, $supported, true)) {
            $locale = $default;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
