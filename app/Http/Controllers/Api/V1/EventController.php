<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\EventStatResource;

class EventController extends Controller
{
    use HandlesLocale;

    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $events = EventResource::collection($this->eventService->getAll($request));
            $paginatedData = PaginateResource::make($events, EventResource::class);
            return response()->json([
                'events' => $paginatedData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events', 'message' => $e->getMessage()], 500);
        }
    }

    public function stats(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $stats = new EventStatResource($this->eventService->getStat());
            return response()->json([
                'stats' => $stats,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch stats for events', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, Event $event)
    {
        try {
            $this->setAndGetLocale($request);
            $event = $this->eventService->getById($event->id);
            return response()->json([
                'event' => new EventResource($event),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch the event', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(StoreEventRequest $request)
    {
        try {
            $event = $this->eventService->create($request->validated());
            return response()->json([
                'event' => new EventResource($event),
                'message' => 'Event created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create event', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            // убедимся, что работаем с route id
            $payload = $request->validated();
            $updated = $this->eventService->update($event->id, $payload);

            return response()->json([
                'event' => new EventResource($updated),
                'message' => 'Event updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to update event',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Event $event)
    {
        try {
            $this->eventService->delete($event->id);
            return response()->json([
                'message' => 'Event deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete event', 'message' => $e->getMessage()], 500);
        }
    }
}