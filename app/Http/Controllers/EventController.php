<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;
use Illuminate\Support\Str;
use App\Models\Event;

class EventController extends Controller
{
    use HandlesLocale;

    protected $event;

    public function __construct(EventService $event) { $this->event = $event; }

    public function index(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $request->merge(['per_page' => 12]);
            $events = $this->event->getActivated($request);
            return view('main.events.index', compact('events'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, string $slug)
    {
        $this->setAndGetLocale($request);
        $locale = app()->getLocale();

        // Берём опубликованное мероприятие по slug
        $event = Event::with(['translations', 'gallery', 'participants'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // (опц.) Лёгкий счётчик просмотров
        try { $event->increment('views'); } catch (\Throwable $e) {}

        // Перевод: как в твоём списке (по language_id == $locale, иначе первый)
        $tr = collect($event->translations)->firstWhere('language_id', $locale)
            ?? collect($event->translations)->first();

        // Даты/статусы
        $now  = now();
        $start = $event->start_time ? \Carbon\Carbon::parse($event->start_time) : null;
        $end   = $event->end_time   ? \Carbon\Carbon::parse($event->end_time)   : null;

        $participantsCount = method_exists($event, 'countParticipants')
            ? $event->countParticipants()
            : (int)($event->participants_count ?? 0);

        $hasSpots  = $event->max_participants > 0 ? $participantsCount < $event->max_participants : true;
        $remaining = max(0, (int)$event->max_participants - $participantsCount);
        $fewSpots  = $remaining > 0 && $remaining <= 3;
        $registrationOpen = $event->registration_deadline ? $now->lte($event->registration_deadline) : true;
        $eventPassed = $end ? $now->gt($end) : false;

        // SEO
        $pageTitle = trim(($tr['title'] ?? $event->title).' — '.config('app.name'));
        $metaDesc  = Str::limit(strip_tags($tr['short_description'] ?? $tr['full_description'] ?? ''), 160);
        $mainImage = $event->image
            ? (preg_match('/^https?:\/\//i', $event->image) ? $event->image : asset('storage/'.$event->image))
            : asset('storage/placeholders/1200x630.png');

        return view('main.events.show', compact(
            'event','tr','pageTitle','metaDesc','mainImage','start','end',
            'hasSpots','remaining','fewSpots','registrationOpen','eventPassed'
        ));
    }
}
