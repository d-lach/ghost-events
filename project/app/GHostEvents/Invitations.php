<?php

namespace App;

use App\Mailing\Mailing;
use Illuminate\Support\Facades\DB;

/**
 * Class Events - defines logic of events modification
 * @package App
 */
class Invitations
{
    /**
     * @var Mailing
     */
    private $mailer;

    function __construct(Mailing $mailer) {
        $this->mailer = $mailer;
    }

    function invite(int $userId, $eventOrId)
    {

        $event = $this->_retrieveEvent($eventOrId);

        print("going to invite user#" . $userId . " to Event-" . $event->id . " ");
//        var_dump($this->mailer);
        $this->mailer->sendTest();

        if ($event->hasGuest($userId)) // cannot invite someone who is already a guest of given event
            return;

        $event->invite($userId);
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