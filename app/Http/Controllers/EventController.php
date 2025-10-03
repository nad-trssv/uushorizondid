<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;

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

}
