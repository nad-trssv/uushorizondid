<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

class EventRepository
{
    public function getAll($request): LengthAwarePaginator
    {
        $query = Event::with(['translations', 'seo.translations', 'gallery', 'participants'])
            ->withCount(['participants', 'confirmedParticipants']);

        // Фильтрация по статусу
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Фильтрация по дате
        if ($request->has('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('start_time', '<=', $request->date_to);
        }

        // Поиск
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Сортировка
        $sortBy = $request->get('sort_by', 'start_time');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($request->get('per_page', 10));
    }

    public function getById($id): Event
    {
        return Event::with([
            'translations.language', 
            'seo.translations.language', 
            'gallery', 
            'participants'
        ])->findOrFail($id);
    }

    public function getStat(): array
    {
        $totalEvents = Event::count();
        $totalViews = Event::sum('views');
        $totalParticipants = \App\Models\EventParticipant::count();
        $totalConfirmedParticipants = \App\Models\EventParticipant::confirmed()->count();

        $byStatus = Event::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status')
            ->toArray();

        $mostPopularEvent = Event::withCount('confirmedParticipants')
            ->orderBy('confirmed_participants_count', 'desc')
            ->first();

        $mostViewedEvent = Event::orderBy('views', 'desc')->first();

        $upcomingEvents = Event::where('start_time', '>', now())->count();
        $pastEvents = Event::where('start_time', '<', now())->count();

        return [
            'total_count' => $totalEvents,
            'total_views' => $totalViews,
            'total_participants' => $totalParticipants,
            'total_confirmed_participants' => $totalConfirmedParticipants,
            'by_status' => $byStatus,
            'most_popular_event' => $mostPopularEvent,
            'most_viewed_event' => $mostViewedEvent,
            'upcoming_events' => $upcomingEvents,
            'past_events' => $pastEvents,
            'average_participation_rate' => $totalEvents > 0 ? round($totalConfirmedParticipants / $totalEvents, 2) : 0,
        ];
    }
}