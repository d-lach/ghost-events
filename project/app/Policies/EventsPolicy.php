<?php

namespace App\Policies;

use App\User;
use App\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {

    }

    /**
     * Determine whether the user can create events.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        print ("test of events policy update");
        return $user->id === $event->host()->id;
    }

    public function edit(User $user, Event $event)
    {
        return $user->id === $event->host()->id;
    }

    public function join(User $user, Event $event){
        return !$event->private || $event->host()->id === $user->id ||$event->isInvited($user->id);
    }

    public function invite(User $user, Event $event){
        return $event->isInvited($user->id) || $event->host()->id === $user->id;
    }

    public function access(User $user, Event $event) {
        print ("test of events policy canAccess");
        return !($event->private)
            || $event->isInvited($user->id)
            || ($event->host()->id === $user->id)
            || $event->hasGuest($user->id);
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can restore the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function restore(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function forceDelete(User $user, Event $event)
    {
        //
    }
}
