<?php

namespace App\Http\Controllers\Api\V1\Traits;

use Illuminate\Http\Request;

trait HandlesFrontLocale
{
    /**
     * Устанавливает локаль для приложения и возвращает текущую локаль
     * 
     * @param Request $request
     * @return string
     */
    protected function setAndGetLocale(Request $request): string
    {
        return app()->getLocale();
    }
}