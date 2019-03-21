<?php

namespace App;

use App\Mailing\Mailing;
use Illuminate\Support\Facades\DB;

/**
 * Class Events - defines logic of events modification
 * @package App
 */
class Events
{
    /**
     * @var Mailing
     */
    private $mailer;

    function __construct(Mailing $mailer) {
        $this->mailer = $mailer;
    }

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

    function allAvailableForUser(User $user)
    {
        return $this->allPublicOpen()
            ->union($user->toHostPrivate())
            ->union($user->toAttendPrivate())
            ->union($user->privateOpenInvitations());
    }

    function allWithNumberOfGuests()
    {
        return Event::select('events.*', DB::raw('count(events.id) as numberOfGuests'))
            ->join('events_guests', 'events.id', '=', 'events_guests.event_id', 'left')
            ->groupBy('events.id');
    }

    function allPublicOpen()
    {
        return Event::where('private', '=', 0)
            ->whereRaw('starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('closes_at > CURRENT_TIMESTAMP()');
    }

    function hostedByUserIds($user)
    {
        return $user->hostedIds();
    }

    function attendedByUserIds($user)
    {
        return $user->attendedIds();
    }

    function getAllHostedBy(int $userId)
    {
        return $this->allWithNumberOfGuests()
            ->join('events_hosts', function ($join) use ($userId) {
                $join->on('events.id', '=', 'events_hosts.event_id');
                $join->where('events_hosts.user_id', '=', $userId);
            });
    }

    function getAllAttended(int $userId)
    {
        return $this->allWithNumberOfGuests()
            ->join('events_guests as eg', function ($join) use ($userId) {
                $join->on('events.id', '=', 'eg.event_id');
                $join->where('eg.user_id', '=', $userId);
            });
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
        $event->removeInvitation($userId);
        $event->addGuest($userId);
    }

    function removeGuest(int $userId, $eventOrID)
    {
        $event = $this->_retrieveEvent($eventOrID);
        $event->removeGuest($userId);
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