<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_invitations', $this->initializingEventsUsersRelationTable());
        Schema::create('events_guest', $this->initializingEventsUsersRelationTable());
        Schema::create('events_hosts', $this->initializingEventsUsersRelationTable());
    }

    private function initializingEventsUsersRelationTable() {
        return function (Blueprint $table) {
            $table->bigInteger('event_id')->unsigned();
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(array('event_id', 'user_id'));
        };
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_invitations');
        Schema::dropIfExists('events_guest');
        Schema::dropIfExists('events_hosts');
    }
}
