<?php

namespace App\Http\Controllers\events;

use App\Events;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Events $events)
    {
//        $this->middleware('auth');
    }

    public function eventsList()
    {
        return view('events.list');
    }

    public function eventsMap()
    {
        return view('events.map');
    }

    public function event($id) {
        return view('events.event');
    }
}
