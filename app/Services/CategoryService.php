<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(protected CategoryRepository $repo) {}

    public function getRootWithChildren($locale)
    {
        return $this->repo->getRootWithChildren($locale);
    }

    public function getByIdWithTranslations($id, $locale)
    {
        return $this->repo->findWithTranslations($id, $locale);
    }

    public function store($data)
    {
        return $this->repo->create($data);
    }
}
