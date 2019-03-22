<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');

            // info
            $table->string('name');
            $table->text('description');
            $table->integer('maxGuests');

            // dates
            $table->dateTime('starts_at'); // when event starts
            $table->dateTime('ends_at'); // when event ends
            $table->dateTime('closes_at'); // end of submissions

            // localisation
            $table->string('street');
            $table->string('city');
            $table->string('zipCode');
            $table->float('latitude');
            $table->float('longitude');

            // status
            $table->boolean('private');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('events');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
