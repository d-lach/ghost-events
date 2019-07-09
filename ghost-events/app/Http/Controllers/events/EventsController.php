<?php

namespace App\Http\Controllers\events;

use App\Event;
use App\EventsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{

    private $events = null;

    public function __construct(EventsRepository $events)
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

    public function getEvent($id)
    {
        return view('events.event');
    }

    public function eventEdit($eventId)
    {
        $this->authorize('edit', Event::find($eventId));
        return view('events.creator', $this->events->getFull($eventId));
    }

    public function eventNew()
    {
        return view('events.creator');
    }

    public function userEvents()
    {
        return view('events.user-events', ["events" => $this->events->getAllHostedBy(Auth::id())->get()]);
    }

    public function userAsGuestEvents()
    {
        return view('events.user-events', ["events" => $this->events->getAllAttended(Auth::id())->get()]);
    }
}
