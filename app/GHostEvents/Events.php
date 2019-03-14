<?php

namespace App;

use App\Exceptions\PrivateEventException;
use Illuminate\Support\Facades\DB;

/**
 * Class Events - defines logic of events modification
 * @package App
 */
class Events
{

    /**
     * @param User|null $userOrNull
     * @return array|string
     */
    function findAllAvailable(?User $userOrNull)
    {
        if ($userOrNull === null)
            return $this->allWithNumberOfGuests()
                ->where(['private' => false])
                ->whereRaw('starts_at > CURRENT_TIMESTAMP()')
                ->whereRaw('closes_at > CURRENT_TIMESTAMP()')// return public events
                ->orderBy('starts_at', 'ASC');

        return $this->allWithNumberOfGuests()
            ->joinSub(
                $this->allAvailableForUser($userOrNull),
                'available',
                'events.id', '=', 'available.id'
            )
            ->orderBy('starts_at', 'ASC');
    }

    function allAvailableForUser(User $user) {
        return $this->allPublicOpen()
            ->union($user->toHostPrivate())
            ->union($user->toAttendPrivate())
            ->union($user->privateOpenInvitations());
    }

    function allWithNumberOfGuests() {
        return Event::select('events.*', DB::raw('count(events.id) as numberOfGuests'))
            ->join('events_guests', 'events.id', '=', 'events_guests.event_id', 'left')
            ->groupBy('events.id');
    }

    function allPublicOpen() {
        return Event::where('private', '=', 0)
            ->whereRaw('starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('closes_at > CURRENT_TIMESTAMP()');
    }

    function getAllHostedBy(int $userId)
    {
        return User::find($userId)->hosted()->get();
    }

    function create(int $userId, array $eventData)
    {
        $event = Event::create($eventData);
        $this->setAsHost($userId, $event);

        return $event;
    }

    function update(int $eventId, array $eventData)
    {
        $event = Event::updateOrCreate(['id' => $eventId], $eventData);
        $event->save();

        return $event;
    }

    function setAsHost(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);

        $host = $event->host();
        if ($host !== null)
            $host->delete(); // remove other hosts
        $event->addHost($userId);

        // host user is also a guest
        if (!$event->hasGuest($userId)) {
            $this->setAsGuest($userId, $event);
        }
    }

    function setAsGuest(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);

        // user cannot join private event without invitation
        if ($event->private && !($event->isInvited($userId))) {
            throw new PrivateEventException();
        }


        $event->removeInvitation($userId);
        $event->addGuest($userId);
    }

    function removeGuest(int $userId, $eventOrID)
    {
        $event = $this->_retrieveEvent($eventOrID);


        $event->removeGuest($userId);
    }

    function invite(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);
        if ($event->hasGuest($userId))
            return;

        $event->invite($userId);
    }


    function getFull($eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);
        return ["event" => $event, "guests" => $event->guests()->get(), "host" => $event->host()];
    }

    function getGuestsList($eventOrId)
    {
        return $this->_retrieveEvent($eventOrId)->guests()->get();
    }

    function getHost($eventOrId)
    {
        return $this->_retrieveEvent($eventOrId)->host();
    }

    /**
     * @param int|Event $eventOrId
     * @return Event
     */
    private function _retrieveEvent($eventOrId)
    {
        if (is_numeric($eventOrId))
            return Event::find($eventOrId);

        return $eventOrId;
    }
}