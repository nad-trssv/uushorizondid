<?php

namespace App\Repositories;

use App\Models\Appointments;

class AppointmentRepository
{
    protected $model;
    public function __construct(Appointments $appointments)
    {
        $this->model = $appointments;
    }
    public function getAll()
    {
        return $this->model::all();
    }

    public function getByUserId($userId)
    {
        return $this->model::with('service')->where('user_id', $userId)->get();
    }

    public function findById($id)
    {
        return $this->model::find($id);
    }
}
