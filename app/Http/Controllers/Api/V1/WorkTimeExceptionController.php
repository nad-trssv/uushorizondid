<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkTimeExceptionRequest;
use App\Http\Resources\WorkTimeExceptionResource;
use App\Services\WorkTimeExceptionService;
use Illuminate\Http\Request;

class WorkTimeExceptionController extends Controller
{
    protected WorkTimeExceptionService $service;

    public function __construct(WorkTimeExceptionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $exceptions = $this->service->getAll();
        return WorkTimeExceptionResource::collection($exceptions);
    }

    public function show($id)
    {
        $exception = $this->service->findById($id);
        return new WorkTimeExceptionResource($exception);
    }

    public function store(WorkTimeExceptionRequest $request)
    {
        $exception = $this->service->create($request->validated());
        return new WorkTimeExceptionResource($exception);
    }

    public function update(WorkTimeExceptionRequest $request, $id)
    {
        $exception = $this->service->update($id, $request->validated());
        return new WorkTimeExceptionResource($exception);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
