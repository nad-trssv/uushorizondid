<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function getMasters()
    {
        $data = $this->repository->getMasters();
        return $data;
    }
}
