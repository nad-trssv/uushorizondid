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
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

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

    public function calendarEvents(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $events = EventResource::collection($this->eventService->getCalendarEvents($request));
            return response()->json([
                'events' => $events,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch calendar events', 'message' => $e->getMessage()], 500);
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

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                File::image()->types(['jpg','jpeg','png','webp','avif', 'heic', 'svg'])->max(5 * 1024), // 5MB
            ],
        ]);
        $dir = 'events';

        $ext = $request->file('file')->extension();
        $filename = Str::uuid() . '.' . $ext;

        $path = $request->file('file')->storeAs($dir, $filename, 'public');

        return response()->json([
            'path' => $path,                         
            'url'  => asset('storage/' . $path),      
            'message' => 'Image uploaded successfully',
        ], 201);
    }

    public function uploadGallery(Request $request, Event $event)
    {
        $isMultiple = $request->hasFile('files');
        $isSingle   = $request->hasFile('file');
    
        if (!$isMultiple && !$isSingle) {
            return response()->json([
                'message' => 'No files were uploaded',
                'errors'  => ['files' => ['The files field is required.']]
            ], 422);
        }
    
        // Валидируем по ситуации
        if ($isMultiple) {
            $request->validate([
                'files'   => ['required','array','min:1'],
                'files.*' => [File::image()->types(['jpg','jpeg','png','webp','avif','heic','svg'])->max(5 * 1024)],
            ]);
        } else { // одиночный
            $request->validate([
                'file' => [ 'required', File::image()->types(['jpg','jpeg','png','webp','avif','heic','svg'])->max(5 * 1024) ],
            ]);
        }
    
        $dir = 'events/gallery';
        $files = $isMultiple ? $request->file('files') : [$request->file('file')];
        $created = [];
    
        foreach ($files as $file) {
            $ext = $file->extension();
            $filename = \Str::uuid().'.'.$ext;
            $path = $file->storeAs($dir, $filename, 'public');
    
            $g = new \App\Models\Gallery();
            $g->image = $path;
            $g->alt = null;
            $g->galleryable()->associate($event);
            $g->save();
    
            $created[] = [
                'id'   => $g->id,
                'path' => $g->image,
                'url'  => asset('storage/'.$g->image),
                'alt'  => $g->alt,
            ];
        }
    
        return response()->json([
            'items'   => $created,
            'message' => 'Gallery images uploaded',
        ], 201);
    }

    public function updateGallery(Request $request, Event $event, Gallery $gallery)
    {
        // безопасность: убеждаемся, что фото принадлежит событию
        if ($gallery->galleryable_type !== Event::class || (int)$gallery->galleryable_id !== (int)$event->id) {
            return response()->json(['message' => 'Gallery item not found for this event'], 404);
        }

        $data = $request->validate([
            'alt' => ['nullable','string','max:255'],
        ]);

        $gallery->alt = $data['alt'] ?? null;
        $gallery->save();

        return response()->json([
            'item' => [
                'id'   => $gallery->id,
                'path' => $gallery->image,
                'url'  => asset('storage/'.$gallery->image),
                'alt'  => $gallery->alt,
            ],
            'message' => 'Gallery item updated',
        ], 200);
    }

    public function destroyGallery(Event $event, Gallery $gallery)
    {
        if ($gallery->galleryable_type !== Event::class || (int)$gallery->galleryable_id !== (int)$event->id) {
            return response()->json(['message' => 'Gallery item not found for this event'], 404);
        }

        // удаляем файл (если лежит в public диске)
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return response()->json(['message' => 'Gallery item deleted'], 200);
    }

}