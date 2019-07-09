<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\Invitation;
use App\InvitationsService;
use App\Mailing\Mailing;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{

    /**
     * @var EventsRepository
     */
    private $eventsRepository;

    /**
     * @var Mailing
     */
    private $emails;

    /**
     * @var Mailing
     */
    private $invitationsService;

    public function __construct(InvitationsService $invitationsService, EventsRepository $eventsRepository, Mailing $emails)
    {
        $this->invitationsService = $invitationsService;
        $this->eventsRepository = $eventsRepository;
        $this->emails = $emails;
    }

    public function invite(Request $request, int $eventId)
    {
        $this->authorize('invite', Event::find($eventId));
        foreach ($request->post('usersIds') as $userId) {
            $this->invitationsService->invite($userId, $eventId);
        }

        return "Ok";
    }


    function invitationAccepted(string $invitationToken)
    {
        $event = $this->invitationsService->accept($invitationToken);
        if ($event) {
            return redirect()->route('event.page', ['eventId' => $event->id]);
        }

        return view('events.invitation-expired');
    }
}
