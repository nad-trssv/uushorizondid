<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;

class SiteSettingController extends Controller
{
    use HandlesLocale;
    public function __construct(
        protected SiteSettingService $service
    ) {}

    // Получить все группы и их настройки
    public function index(Request $request)
    {
        $this->setAndGetLocale($request);
        $settings = $this->service->getAll($request);
        $languages = $this->service->getLanguages();
        $currentLang = $this->service->currlang();

        return response()->json([
            'languages' => $languages,
            'current_language' => $currentLang,
            'settings' => $settings->map(function ($items, $group) {
                return [
                    'group' => $group,
                    'settings' => SiteSettingResource::collection($items)
                ];
            })->values()
        ]);
    }

    // Получить одну группу по названию
    public function show(string $group)
    {
        $settings = $this->service->getGroup($group);

        return SiteSettingResource::collection($settings);
    }

    // Обновить группу
    public function update(Request $request, string $group)
    {
        $updated = $this->service->updateGroup($group, $request->all());

        return SiteSettingResource::collection($updated);
    }
}
