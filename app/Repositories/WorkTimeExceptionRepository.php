<?php

namespace App\Repositories;

use App\Models\WorkTimeException;

class WorkTimeExceptionRepository
{
    public function getAll()
    {
        return WorkTimeException::orderBy('date')->get();
    }

    public function findById(int $id)
    {
        return WorkTimeException::findOrFail($id);
    }

    public function create(array $data)
    {
        return WorkTimeException::create($data);
    }

    public function update(int $id, array $data)
    {
        $exception = $this->findById($id);
        $exception->update($data);
        return $exception;
    }

    public function delete(int $id)
    {
        $exception = $this->findById($id);
        return $exception->delete();
    }
}
