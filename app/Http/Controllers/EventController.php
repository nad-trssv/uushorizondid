<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EventParticipant;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

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

    public function show(Request $request)
    {
        $this->setAndGetLocale($request);
        $locale = app()->getLocale();
        $slug = (string) $request->route('slug');

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

    public function calendar(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $locale = app()->getLocale();

            // Берём опубликованные мероприятия с переводами (без тяжёлых связей)
            $events = Event::with(['translations'])
                ->where('status', 'published')
                ->orderBy('start_time', 'asc')
                ->get();

            // Подготовим минимальные вычисляемые поля для удобства вывода
            $now = now();
            $events->transform(function ($e) use ($locale, $now) {
                $tr = collect($e->translations)->firstWhere('language_id', $locale)
                    ?? collect($e->translations)->first();

                $e->ui_title     = $tr['title'] ?? $e->title ?? 'Без названия';
                $e->ui_location  = $tr['location'] ?? null;
                $e->ui_start     = $e->start_time ? \Carbon\Carbon::parse($e->start_time) : null;
                $e->ui_end       = $e->end_time   ? \Carbon\Carbon::parse($e->end_time)   : null;
                $e->ui_date_key  = $e->ui_start ? $e->ui_start->format('Y-m') : null;   // для клиентского фильтра по месяцу
                $e->ui_passed    = $e->ui_end ? $now->gt($e->ui_end) : false;
                $e->ui_reg_open  = $e->registration_deadline ? $now->lte($e->registration_deadline) : true;

                return $e;
            });

            return view('main.events.calendar', compact('events', 'locale'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load calendar', 'message' => $e->getMessage()], 500);
        }
    }
    public function feed(Request $request)
    {
        $this->setAndGetLocale($request);
        $events = Event::with('translations')
            ->where('status', 'published')
            ->where('start_time', '>=', now()->subMonths(1))
            ->get();
    
        $data = $events->map(function ($event) {
            $locale = app()->getLocale();
            $translation = collect($event->translations)->firstWhere('language_id', $locale) ?? collect($event->translations)->first();
            
            $now = now();
            $eventPassed = $now->gt($event->end_time);
            $registrationOpen = $event->registration_deadline && $now->lte($event->registration_deadline);
            $participantsCount = $event->countParticipants();
            $hasSpots = $participantsCount < $event->max_participants;
            $remainingSpots = $event->max_participants - $participantsCount;
    
            // Определяем статус и класс
            if ($eventPassed) {
                $status = 'Прошло';
                $statusClass = 'past';
                $color = '#94A3B8';
            } elseif (!$registrationOpen) {
                $status = 'Регистрация закрыта';
                $statusClass = 'closed';
                $color = '#9CA3AF';
            } elseif (!$hasSpots) {
                $status = 'Нет мест';
                $statusClass = 'full';
                $color = '#facc15';
            } else {
                $status = 'Регистрация открыта';
                $statusClass = 'open';
                $color = '#7DA640';
            }
    
            return [
                'id' => $event->id,
                'title' => $translation['title'] ?? 'Без названия',
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->end_time->toIso8601String(),
                'url' => URL::locale_url(app()->getLocale(), 'events.show', ['slug' => $event->slug]),
                'color' => $color,
                'extendedProps' => [
                    'status' => $status,
                    'statusClass' => $statusClass,
                    'price' => $event->price > 0 ? number_format($event->price, 2) . ' €' : 'Бесплатно',
                    'description' => \Illuminate\Support\Str::limit(strip_tags($translation['short_description'] ?? ''), 120, '...'),
                    'image' => $event->image ? "/storage/{$event->image}" : null,
                    'location' => $event->location, // если есть поле location
                ]
            ];
        });
    
        return response()->json($data);
    }

    public function register(Request $request)
    {
        $request->validate([
            'event_id'   => 'required|exists:events,id',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:150',
            'phone'      => 'nullable|string|max:50',
        ]);

        $data = $request->only(['event_id', 'first_name', 'last_name', 'email', 'phone']);
        $event = Event::findOrFail($data['event_id']);
        if ($event->status !== 'published') {
            return redirect()->back()->with('error', 'Регистрация на это мероприятие невозможна, так как оно не опубликовано.');
        }
        if ($event->registration_deadline && now()->gt($event->registration_deadline)) {
            return redirect()->back()->with('error', 'Срок регистрации на это мероприятие истёк.');
        }
        if ($event->max_participants > 0) {
            $participantsCount = method_exists($event, 'countParticipants')
                ? $event->countParticipants()
                : (int)($event->participants_count ?? 0);
            if ($participantsCount >= $event->max_participants) {
                return redirect()->back()->with('error', 'К сожалению, все места на это мероприятие уже заняты.');
            }
        }
        if (EventParticipant::where('event_id', $event->id)
            ->where(function ($query) use ($data) {
                $query->where('email', $data['email'])
                      ->orWhere('phone', $data['phone']);
            })->exists()) {
            return redirect()->back()->with('error', 'Вы уже зарегистрированы на это мероприятие.');
        }


        $data['status'] = 'confirmed';
        $data['participants_count'] = ($event->participants_count ?? 0) + 1;
        $event->update(['participants_count' => $data['participants_count']]);
        $participantData= [
            'event_id' => $event->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'phone' => $data['phone'],
            'participants_count' => $data['participants_count'],
        ];
        EventParticipant::create($participantData);
        return redirect()->back()->with('success', 'Вы успешно зарегистрированы на мероприятие.');
    }
}
