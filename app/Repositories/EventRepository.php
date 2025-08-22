<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository
{
    protected $model;

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function getAll()
    {
        return $this->model->orderBy('start_at')->get();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $event = $this->findById($id);
        $event->update($data);
        return $event;
    }

    public function delete($id)
    {
        $event = $this->findById($id);
        return $event->delete();
    }
}
