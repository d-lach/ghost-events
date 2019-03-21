<?php

namespace App\Http\Controllers\events;

use App\Event;
use App\Events;
use App\Http\Controllers\Controller;
use App\Invitations;
use App\Mailing\Mailing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

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
            $this->events->invite($userId, $eventId);
        }
    }


    function invitationAccepted(int $invitationId) {

    }
}
