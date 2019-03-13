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

        $allPublic = DB::table('events')
            ->where('private', '=', 0)
            ->whereRaw('starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('closes_at > CURRENT_TIMESTAMP()');

        $buildUserRelatedEventsQuery = function($table) use ($userIdOrNulll) {
            return DB::table('events')
                ->select('events.*')
                ->join($table, function ($join) use($table, $userIdOrNulll) {
                    $join->on('events.id', '=', $table . '.event_id')
                        ->where($table . '.user_id', '=', $userIdOrNulll);
                })
                ->where('events.private', '=', true)
                ->whereRaw('events.starts_at > CURRENT_TIMESTAMP()')
                ->whereRaw('events.closes_at > CURRENT_TIMESTAMP()');
        };

        $hostedByUser =  $buildUserRelatedEventsQuery('events_hosts');
        $userIsGuest =  $buildUserRelatedEventsQuery('events_guests');
        $userIsInvited =  $buildUserRelatedEventsQuery('events_invitations');


        return $allPublic
            ->union($hostedByUser)
            ->union($userIsGuest)
            ->union($userIsInvited)
            ->orderBy('starts_at', 'ASC')
            ->get()->toArray();

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