<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvitationsTokens extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events_invitations', function ($table) {
            $table->string('token', 32)
                ->unique();
//                ->nullable()
//                ->default(null);

            $table->dateTime('token_expires_at')
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        Schema::dropIfExists('users');
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
