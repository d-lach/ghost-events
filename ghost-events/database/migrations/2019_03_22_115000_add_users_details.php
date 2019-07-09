<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersDetails extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {

            $table->enum('gender', \App\User\Genders::all)
                ->after('name')
                ->default(\App\User\Genders::Another);

//            DB::statement("ALTER TABLE users MODIFY gender enum('Male', 'Female', 'Another') NOT NULL;");

            $table->date('birthday')
                ->after('gender');
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
