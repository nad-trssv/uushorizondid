<?php

namespace App\Http\Controllers\Api\V1\Traits;

use Illuminate\Http\Request;

trait HandlesLocale
{
    /**
     * Устанавливает локаль для приложения и возвращает текущую локаль
     * 
     * @param Request $request
     * @return string
     */
    protected function setAndGetLocale(Request $request): string
    {
        $locale = $request->header('Accept-Language', 'en');
        app()->setLocale($locale);
        
        return $request->get('locale', $locale);
    }
}