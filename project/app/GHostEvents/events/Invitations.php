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

        // cannot invite someone who is already invited to or is a guest of given event
        if ($event->hasGuest($userId) || $event->isInvited($userId))
            return;
        
        $invitation = Invitation::make();

        $userToInvite = User::find($userId);

        $invitation->guest()->associate($userToInvite);
        $invitation->event()->associate($event);

        $invitationToken = $invitation->freshToken();

        $eventUrl = route('event.page', ['eventId' => $event->id]);
        $invitationUrl = route('invitation.confirmation', ['confirmationToken' => $invitationToken]);

        $this->mailer->newHTMLMail()
            ->to($userToInvite->email)
            ->as($userToInvite->name)
            ->subject("Invitation " . $event->name)
            ->content(
                view('emails.event-invitation-email', [
                    'eventLink' => $eventUrl,
                    'acceptInvitationLink' => $invitationUrl,
                    'event' => $event
                ])->render())
            ->send();

        $invitation->save();
    }

    function accept($invitationToken)
    {
        $invitation = Invitation::where('token', '=', $invitationToken)->first();


        if (!$invitation || !$invitation->isValid()) {
            return false;
        }

        $event = $invitation->event;
        $invitation->event->addGuest($invitation->guest->id);
        $invitation->delete();

        return $event;

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