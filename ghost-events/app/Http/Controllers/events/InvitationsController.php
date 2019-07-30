<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventsRepository;
use App\UsersRepository;
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
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * @var Mailing
     */
    private $invitationsService;

    public function __construct(
        InvitationsService $invitationsService,
        EventsRepository $eventsRepository,
        UsersRepository $usersRepository
    )
    {
        $this->invitationsService = $invitationsService;
        $this->eventsRepository = $eventsRepository;
        $this->usersRepository = $usersRepository;
    }

    public function invite(Request $request, int $eventId)
    {
        $userToInvite = $this->usersRepository->findByEmail($request->post('email'));
        if ($userToInvite === null) {
            return response()->json([
                'errors' => [
                    'email' => 'user not foudn'
                ]
            ]);
        }

        $this->invitationsService->invite($userToInvite, $eventId);
        return "OK";
    }

    public function inviteMany(Request $request, int $eventId)
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
