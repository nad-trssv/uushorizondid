<?php

namespace App\Services;

use App\Repositories\SiteSettingRepository;

class SiteSettingService
{
    public function __construct(
        protected SiteSettingRepository $repository
    ) {}

    public function getAll($request)
    {
        return $this->repository->all($request);
    }

    public function getGroup(string $group)
    {
        return $this->repository->getByGroup($group);
    }

    public function updateGroup(string $group, array $data)
    {
        return $this->repository->updateGroup($group, $data);
    }

    public function getLanguages()
    {
        return $this->repository->getLanguages();
    }
}
