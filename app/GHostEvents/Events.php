<?php

namespace App;

/**
 * Class Events - defines logic of events modification
 * @package App
 */
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
        // print "goscie:" . $event->guests()->get() . " am I # ". $userId . " a guest? " . ($event->hasGuest($userId) ? "yes" : "no");
        if (!$event->hasGuest($userId)) {
            $this->setAsGuest($userId, $event);
        }
    }

    function setAsGuest(int $userId, $eventOrId)
    {
        $event = $this->_retrieveEvent($eventOrId);

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