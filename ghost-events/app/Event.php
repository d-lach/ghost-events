<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $guarded = [
        'id'
    ];

    protected $attributes = [
        'private' => false,
    ];

    protected $appends = [
        'host',
        'guests'
    ];

    function guests()
    {
        return $this->belongsToMany('App\User', 'events_guests');
    }

    function host()
    {
        return $this->belongsToMany('App\User', 'events_hosts')->first();
    }

    function invited()
    {
        return $this->belongsToMany('App\User', 'events_invitations');
    }

    function getHostAttribute()
    {
        return $this->host()->id;
    }

    function getGuestsAttribute()
    {
        return $this->guests()->pluck('user_id');
    }

    function addHost(int $userId)
    {
        DB::table('events_hosts')->insert(
            ['event_id' => $this->id, 'user_id' => $userId]
        );
    }

    function hasGuest(int $userId)
    {
        return $this->guests()
            ->where(['user_id' => $userId])
            ->exists();
    }

    function isInvited(int $userId)
    {
        return $this->invited()
            ->where(['user_id' => $userId])
            ->exists();
    }

    function addGuest(int $userId)
    {
        DB::table('events_guests')->insert(
            ['event_id' => $this->id, 'user_id' => $userId]
        );
    }

    function removeGuest(int $userId)
    {
        DB::table('events_guests')
            ->where(['event_id' => $this->id, 'user_id' => $userId])
            ->delete();
    }

    function invite(int $userId)
    {
        DB::table('events_invitations')->updateOrInsert(
            ['event_id' => $this->id, 'user_id' => $userId],
            ['event_id' => $this->id, 'user_id' => $userId]
        );
    }

    function removeInvitation(int $userId)
    {
        DB::table('events_invitations')
            ->where(['event_id' => $this->id, 'user_id' => $userId])
            ->delete();
    }
}
