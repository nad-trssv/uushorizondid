<?php

namespace App\Repositories;

use App\Models\SiteSetting;

class SiteSettingRepository
{
    public function all($request)
    {
        if ($request->has('group')) {
            return SiteSetting::where('group', $request->get('group'))->get()->groupBy('group');
        }
        return SiteSetting::all()->groupBy('group');
    }

    public function getByGroup(string $group)
    {
        return SiteSetting::where('group', $group)->get()->keyBy('key');
    }

    public function updateGroup(string $group, array $data)
    {
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $value]
            );
        }
        return $this->getByGroup($group);
    }
}
