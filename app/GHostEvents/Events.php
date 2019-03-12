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
     * @param int|null $userIdOrNulll
     * @return array|string
     */
    function findAllAvailable(?int $userIdOrNulll)
    {
        if ($userIdOrNulll === null)
            return Event::where(['private' => false])->get(); // return public events


        return DB::select('
select * from events where events.private = 1 
UNION DISTINCT
 select events.* from events join events_guests on events_guests.event_id = events.id and events_guests.user_id = 1
 UNION DISTINCT
 select events.* from events join events_hosts on events_hosts.event_id = events.id and events_hosts.user_id = 1
UNION DISTINCT
select events.* from events join events_invitations on events_invitations.event_id = events.id and events_invitations.user_id = 1
    ');
    }

    function getAllOf(int $userId)
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
        return true;
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