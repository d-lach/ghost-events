<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
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
