<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(
        protected SiteSettingService $service
    ) {}

    // Получить все группы и их настройки
    public function index(Request $request)
    {
        $settings = $this->service->getAll($request);

        return response()->json(
            $settings->map(function ($items, $group) {
                return [
                    'group' => $group,
                    'settings' => SiteSettingResource::collection($items),
                ];
            })->values()
        );
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
