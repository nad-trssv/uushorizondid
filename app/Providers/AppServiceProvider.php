<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

     public function boot(): void
    {
        URL::macro('locale_url', function (
            string $target,
            ?string $routeName = null,
            array $params = [],
            array $query = [],
            bool $absolute = true
        ) {
            $default   = config('locales.default', 'et');
            $supported = config('locales.supported', ['et','ru','en','uk']);

            // Текущий роут/параметры/квери по умолчанию
            $routeName = $routeName ?: Route::currentRouteName();
            $params    = $params ?: (Route::current()?->parameters() ?? []);
            $query     = $query ?: request()->query();

            // убираем 'locale' из route-парам, чтоб не лез в default-URL
            unset($params['locale']);

            // чистим мусор из query (locale, пагинация и т.п.)
            $query = Arr::except($query, ['locale','lang','hl','page','per_page','paginate']);

            // подбираем корректное имя роута
            $baseName = ltrim($routeName, '.');
            $isPrefixedName = str_starts_with($baseName, 'loc.');
            $bareName = $isPrefixedName ? substr($baseName, 4) : $baseName;

            if ($target === $default) {
                // без префикса
                $nameToUse = $bareName;                // blog.index
                $routeParams = $params;                // без 'locale'
            } else {
                // с префиксом
                $nameToUse = 'loc.'.$bareName;         // loc.blog.index
                $routeParams = array_merge(['locale' => $target], $params);
            }

            // строим URL
            $url = route($nameToUse, $routeParams, $absolute);

            // если остались значимые query (utm и т.д.) — приклеиваем
            if (!empty($query)) {
                $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
            }

            return $url;
        });
    }

}