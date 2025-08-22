<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\ServiceRepository;
use GuzzleHttp\Psr7\Request;

class ServiceService
{
    protected ServiceRepository $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listPaginated($request): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->listPaginated($request);
    }

    public function getAll($locale): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->getAll($locale);
    }

    public function listFiltered($locale, $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->listFiltered($locale, $request);
    }

    public function getById($id, $locale): Service
    {
        return $this->repository->find($id, $locale);
    }

    public function getStat(): array
    {
        $stats = $this->repository->getStat();
        return $stats;
    }

    public function create(array $data): Service
    {
        return $this->repository->create($data);
    }

    public function update(Service $service, array $data): Service
    {
        return $this->repository->update($service, $data);
    }

    public function toggleStatus(Service $service): Service
    {
        return $this->repository->toggleStatus($service);
    }
    public function delete(Service $service): bool
    {
        return $this->repository->delete($service);
    }

    public function addMaster(Service $service, int $masterId): Service
    {
        return $this->repository->addMaster($service, $masterId);
    }
    
    public function removeMaster(Service $service, int $masterId): Service
    {
        return $this->repository->removeMaster($service, $masterId);
    }
}
