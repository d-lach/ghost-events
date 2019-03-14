<?php

namespace App\Http\Controllers\events;

use App\Events;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{

    private $events = null;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function eventsList()
    {
        $pagination = $this->events->findAllAvailable(Auth::user())
            ->paginate(Input::get('perPage', 25));

        $events = json_encode($pagination->toArray()['data']);

        return view('events.list', [
            'wholePaginationElement' => $pagination,
            'events' => $events
        ]);
    }

    public function eventsMap()
    {
        return view('events.map');
    }

    public function event($id)
    {
        return view('events.event');
    }
}
