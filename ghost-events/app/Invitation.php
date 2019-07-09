<?php

namespace App;

use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class Invitation extends Model
{

    protected $table = 'events_invitations';

    public $timestamps = false;

   /* protected $primaryKey = [
        'event_id',
        'user_id'
    ];*/

    function freshToken() {
        $this->token = bin2hex(openssl_random_pseudo_bytes(32));

//        $start_date = new DateTime();

        $this->token_expires_at = (new DateTime())
            ->add(DateInterval::createFromDateString('1 week'))
            ->format('Y-m-d H:i');
//        $start_date->format('Y-m-d H:i');
        //date('Y-m-d H:i', $date_to_pay);
//        $attributes['token'] =
//        $attributes['token_expires_at'] =
        return $this->token;
    }

    function guest()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');//->first();
    }

    function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }

    function isValid() {
        $now = new DateTime('now');
        $expiration = new DateTime($this->token_expires_at);
        return $expiration > $now;
    }

  function delete() {
      DB::table('events_invitations')
          ->where('event_id', '=', $this->value('event_id'))
          ->where('user_id', '=', $this->value('user_id'))
          ->delete();
  }


}
