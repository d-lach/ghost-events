<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return   void
     */
    public function run()
    {
        $users = [];
        $passwordEncrypted = bcrypt('testowe');

        for ($i = 0; $i < 500; $i++) {
            $users[] = [
                'name' => 'test x' . $i,
                'email' => 'testx'. $i . '@test',
                'password' => $passwordEncrypted
            ];
        }

        DB::table('users')->insert($users);
    }
}
