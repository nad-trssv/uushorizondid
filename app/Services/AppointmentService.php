<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;

class AppointmentService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected AppointmentRepository $repo){}
    
    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function getByUserId($userId)
    {
        return $this->repo->getByUserId($userId);
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }
}
