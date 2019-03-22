<?php

use Illuminate\Database\Seeder;
use App\Libs\Utilities\Random;
use App\User\Genders;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [];
        $passwordEncrypted = bcrypt('testowe');

        for ($i = 0; $i < 500; $i++) {
            $users[] = [
                'name' => 'test x' . $i,
                'gender' => Genders::all[Random::int(0, count(Genders::all))],
                'birthday' => Random::date('1975-04-11 09:40', '2010-04-11 09:40'),
                'email' => 'testx'. $i . '@test',
                'password' => $passwordEncrypted
            ];
        }

        DB::table('users')->insert($users);
    }
}
