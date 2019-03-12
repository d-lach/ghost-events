<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            \App\User::create([
                'name' => 'testx' . $i,
                'email' => 'testx'. $i . '@test.com',
                'password' => bcrypt('testowe')
            ]);
        }
    }
}
