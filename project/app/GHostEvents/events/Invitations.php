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

    function __construct(Mailing $mailer)
    {
        $this->mailer = $mailer;
    }

    function invite(int $userId, $eventId)
    {

        $event = $this->_retrieveEvent($eventId);

        if ($event->hasGuest($userId)) // cannot invite someone who is already a guest of given event
            return;

        print("going to invite user#" . $userId . " to Event-" . $eventId . " ");

        $invitation = Invitation::make();
        //[
        // 'event_id' => $eventId,
        // 'user_id' => $userId
        //   ]
        //);

        $userToInvite = User::find($userId);

        $invitation->guest()->associate($userToInvite);
        $invitation->event()->associate($event);

        $invitationToken = $invitation->freshToken();

        $eventUrl = route('event.page', ['eventId' => $event->id]);
        $invitationUrl = route('invitation.confirmation', ['eventId' => $event->id]);

        $this->mailer->newHTMLMail()
            ->to("d.lach.321@gmail.com")
            ->as($userToInvite->name)
            ->subject("Invitation " . $event->name)
            ->content(
                view('emails.event-invitation-email', [
                    'eventLink' => "localhost",
                    'acceptInvitationLink' => "localhost",
                    'event' => $event
                ])->render())
            ->send();


//        $event->invite($userId);
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