<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Events
{
    function create(int $userId, array $eventData)
    {
        $event = Event::create($eventData);
        $this->setAsHost($userId, $event);

        return $event;
    }

    function update(int $eventId, array $eventData)
    {
        $event = Event::updateOrCreate(['id' => $eventId], $eventData); //->update($eventData);
        $event->save();
//        $this->setAsHost($userId, $event);

        return $event;
    }


    function setAsHost(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId); // Event::find($eventId);

        $host = $event->host();
        if ($host !== null)
            $host->delete(); // remove other hosts
        $event->addHost($userId);
        // host user is also a guest

        if (!$event->hasGuest($userId)) {
            $this->setAsGuest($userId, $event);
        }

        /* DB::table('events_hosts')->insert(
             ['event_id' => $event->id, 'user_id' => $userId]
         );*/

    }

    function setAsGuest(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);


//        $event =
        $event->removeInvitation($userId);
        $event->addGuest($userId);

//        DB::table('events_guests')->insert(
//            ['event_id' => $eventId, 'user_id' => $userId]
//        );
    }

    function removeGuest(int $userId, $eventOrID)
    {
        $event = $this->_retrieveEvent($eventOrID);


//        $event =
        $event->removeGuest($userId);
//        $event->addGuest($userId);
    }

    function invite(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);
        if ($event->hasGuest($userId))
            return;

        $event->invite($userId);
        /* DB::table('events_invitations')->insert(
             ['event_id' => $eventId, 'user_id' => $userId]
         );*/
    }


    function getFull($eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);
        return ["event" => $event, "guests" => $event->guests(), "host" => $event->host()];
    }

    function getGuestsList($eventOrId)
    {
        return $this->_retrieveEvent($eventOrId)->guests();
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