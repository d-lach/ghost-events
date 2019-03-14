<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QBuilder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function hosted(): BelongsToMany
    {
        return $this->belongsToMany('App\Event', 'events_hosts');
    }

    function toHost(): BelongsToMany
    {
        return $this->hosted()
            ->select('events.*')
            ->whereRaw('events.starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('events.closes_at > CURRENT_TIMESTAMP()');
    }

    function toHostPrivate(): BelongsToMany
    {
        return $this->toHost()->where('events.private', '=', true);
    }

    function attended(): BelongsToMany
    {
        return $this->belongsToMany('App\Event', 'events_guests');
    }

    function toAttend(): BelongsToMany
    {
        return $this->attended()
            ->select('events.*')
            ->whereRaw('events.starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('events.closes_at > CURRENT_TIMESTAMP()');
    }

    function toAttendPrivate(): BelongsToMany
    {
        return $this->toAttend()->where('events.private', '=', true);
    }

    function invitedTo(): BelongsToMany
    {
        return $this->belongsToMany('App\Event', 'events_invitations');
    }

    function openInvitations(): BelongsToMany
    {
        return $this->invitedTo()
            ->select('events.*')
            ->whereRaw('events.starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('events.closes_at > CURRENT_TIMESTAMP()');
    }

    function privateOpenInvitations(): BelongsToMany
    {
        return $this->openInvitations()->where('events.private', '=', true);
    }

    function allRelatedEvents(): QBuilder
    {
        return $this->hosted()
            ->union($this->attended())
            ->union($this->invitedTo())
            ->select('events.*');
    }


    function allOpenRelatedEvents(): QBuilder
    {
        return $this->allRelatedEvents()
            ->whereRaw('events.starts_at > CURRENT_TIMESTAMP()')
            ->whereRaw('events.closes_at > CURRENT_TIMESTAMP()');
    }
}
