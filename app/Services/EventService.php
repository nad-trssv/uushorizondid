<?php

namespace App\Services;

use App\Repositories\EventRepository;

class EventService
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllEvents()
    {
        return $this->repository->getAll();
    }

    public function getEventById($id)
    {
        return $this->repository->findById($id);
    }

    public function createEvent(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateEvent($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteEvent($id)
    {
        return $this->repository->delete($id);
    }
}
