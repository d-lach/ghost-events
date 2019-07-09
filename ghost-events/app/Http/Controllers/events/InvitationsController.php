<?php

namespace App\Http\Controllers;

use App\Events;
use App\Http\Controllers\Controller;
use App\Invitation;
use App\InvitationsService;
use App\Mailing\Mailing;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{

    /**
     * @var Events
     */
    private $events;

    /**
     * @var Mailing
     */
    private $emails;

    /**
     * @var Mailing
     */
    private $invitations;

    public function __construct(InvitationsService $invitations, Events $events, Mailing $emails)
    {
        $this->invitations = $invitations;
        $this->events = $events;
        $this->emails = $emails;
    }

    public function invite(Request $request, int $eventId)
    {
        $this->authorize('invite', Event::find($eventId));
        foreach ($request->post('usersIds') as $userId) {
            $this->invitations->invite($userId, $eventId);
        }

        return "Ok";
    }


    function invitationAccepted(string $invitationToken)
    {
        $event = $this->invitations->accept($invitationToken);
        if ($event) {
            return redirect()->route('event.page', ['eventId' => $event->id]);
        }

        return view('events.invitation-expired');
    }
}
