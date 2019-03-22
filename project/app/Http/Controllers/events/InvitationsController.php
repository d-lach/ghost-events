<?php

namespace App\Http\Controllers;

use App\Events;
use App\Http\Controllers\Controller;
use App\Invitations;
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

    public function __construct(Invitations $invitations, Events $events, Mailing $emails)
    {
        $this->invitations = $invitations;
        $this->events = $events;
        $this->emails = $emails;
    }
    public function invite(Request $request, int $eventId)
    {
        // disabled for testing only
        // $this->authorize('invite', Event::find($eventId));
        foreach ($request->post('usersIds') as $userId) {
            $this->invitations->invite($userId, $eventId);
        }

        return "Ok";
    }


    function invitationAccepted(int $invitationId) {
        return "Dummy confirmation";
    }
}
