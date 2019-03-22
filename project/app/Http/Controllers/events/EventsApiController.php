<?php

namespace App\Http\Controllers;

use App\Event;
use App\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventsApiController extends Controller
{

    private $events = null;

    /**
     * EventsApiController constructor.
     * @param Events $events
     */
    function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function allPaginated(Request $request, int $page)
    {
        $events = $this->events->findAllAvailable(Auth::user())
            ->paginate(Input::get('perPage', 25), ['*'], '', $page);

        return response()->json($events);
    }

    public function all(Request $request)
    {
        $events = $this->events->findAllAvailable(Auth::user())->get();

        return response()->json($events);
    }

    public function mine(Request $request)
    {
        $events = $this->events->getAllHostedBy(Auth::id())->get();
        return response()->json($events);
    }

    public function getIdsOfUserEvents(Request $request)
    {
        return response()->json([
            "hosted" => Auth::user()->hostedIds(),
            "attended" => Auth::user()->attendedIds()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = $this->events->create(1, $this->eventValidator($request->all())->validate());

        return $event;
    }

    public function join(int $eventId)
    {
        $this->authorize('join', Event::find($eventId));
        $this->events->setAsGuest(Auth::id(), $eventId);
    }

    public function leave(int $eventId)
    {
        $this->events->removeGuest(Auth::id(), $eventId);
    }

    public function addGuest(Request $request, int $eventId)
    {
        $this->authorize('edit', Event::find($eventId));
        $this->events->setAsGuest($request->post('userId'), $eventId);
    }

    public function removeGuest(Request $request, int $eventId)
    {
        $this->authorize('edit', Event::find($eventId));
        $this->events->removeGuest($request->post('userId'), $eventId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $eventId
     * @return \Illuminate\Http\Response
     */
    public function show($eventId)
    {
        $this->authorize('access', Event::find($eventId));
        return response()->json($this->events->getFull($eventId));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $eventId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $eventId)
    {
        $this->authorize('edit', Event::find($eventId));

        $validator = $this->eventValidator($request->all(), false);
        $event = $this->events->update($eventId, $validator->validate());

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $eventId
     * @return \Illuminate\Http\Response
     */
    public function destroy($eventId)
    {
        $this->authorize('edit', Event::find($eventId));
    }

    /**
     * @param  array $data
     * @param bool $strict
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function eventValidator(array $data, bool $strict = true)
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:1600'],
            'maxGuests' => ['integer', 'min:1'],
            'starts_at' => ['date_format:Y-m-d H:i', 'after:today'],
            'ends_at' => ['date_format:Y-m-d H:i', 'after:starts_at'],
            'closes_at' => ['date_format:Y-m-d H:i', 'before_or_equal:ends_at'],
            'street' => ['string', 'min:3'],
            'city' => ['string', 'min:3'],
            'zipCode' => ['string', 'regex:/^[0-9]{2}-[0-9]{3}$/'], // zip code format 00-000
            'latitude' => ['numeric'],
            'longitude' => ['numeric'],
            'private' => ['boolean'],
        ];

        if ($strict) {
            $rules = array_map(function ($conditions) {
                array_push($conditions, 'required');
                return $conditions;
            }, $rules);
        }

        return Validator::make($data, $rules);
    }
}
