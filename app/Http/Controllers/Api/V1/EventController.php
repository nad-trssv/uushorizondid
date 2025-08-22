<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    protected $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    // GET /api/v1/events
    public function index()
    {
        $events = $this->service->getAllEvents();
        return EventResource::collection($events);
    }

    // GET /api/v1/events/{id}
    public function show($id)
    {
        $event = $this->service->getEventById($id);
        return new EventResource($event);
    }

    // POST /api/v1/events
    public function store(EventRequest $request): JsonResponse
    {
        $event = $this->service->createEvent($request->validated());
        return (new EventResource($event))->response()->setStatusCode(201);
    }

    // PUT /api/v1/events/{id}
    public function update(EventRequest $request, $id): JsonResponse
    {
        $event = $this->service->updateEvent($id, $request->validated());
        return (new EventResource($event))->response()->setStatusCode(200);
    }

    // DELETE /api/v1/events/{id}
    public function destroy($id): JsonResponse
    {
        $this->service->deleteEvent($id);
        return response()->json(null, 204);
    }
}
