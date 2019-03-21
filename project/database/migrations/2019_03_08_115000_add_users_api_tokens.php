<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersApiTokens extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('api_token', 80)
                ->after('password')
                ->unique()
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
