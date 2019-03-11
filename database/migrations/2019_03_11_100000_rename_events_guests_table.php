<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameEventsGuestsTable extends Migration
{
    public function up()
    {
        Schema::rename('events_guest', 'events_guests');
    }


    public function down()
    {
        Schema::rename('events_guests', 'events_guest');
    }
}
