<?php

use App\User;
use App\Event;
use Illuminate\Database\Seeder;

class RelationsTablesSeeder extends Seeder
{

    private $eventsCounter = 0;
    private $usersCounter = 0;

    private $registeredUsersPerEvent = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->eventsCounter = Event::all()->count();
        $this->usersCounter = User::all()->count();

        $hosts = $this->getRandomizedHosts();

        $guests = $this->getRandomizedGuests();
        $invitations = $this->getRandomizedInvitations();

        DB::table('events_hosts')->insert($hosts);
        DB::table('events_guests')->insert($hosts); // hosts are also guests by default

        DB::insert("insert into `events_guests` (`event_id`, `user_id`) values "
            . implode(", ", $guests)
            ." on duplicate key update event_id = event_id");
        //DB::table('events_guests')->updateOrInsert($guests); //, $guests);

        DB::table('events_invitations')->insert($invitations);
    }

    private function getRandomizedHosts()
    {
        $eventsHosts = [];
        for ($i = 1; $i <= $this->eventsCounter; $i++) {
            $userId = $this->randomUserId();
            $eventsHosts[] = ['event_id' => $i, 'user_id' => $userId];

            if (!array_key_exists($i, $this->registeredUsersPerEvent))
                $this->registeredUsersPerEvent[$i] = [];

            array_push($this->registeredUsersPerEvent[$i], $userId);
        }

        return $eventsHosts;
    }

    private function getRandomizedGuests()
    {
        $eventsGuests = [];
        for ($i = 1; $i <= $this->eventsCounter; $i++) {
            for ($j = 0; $j < rand(0, 10); $j++) {
                $userId = $this->randomUserId();
//                array_push($eventsGuests, ['event_id' => $i, 'user_id' => $userId]);
                $eventsGuests[] = "(" . $i . "," . $userId . ")";
//                array_push($eventsGuests, , $userId);
//
                if (!array_key_exists($i, $this->registeredUsersPerEvent))
                    $this->registeredUsersPerEvent[$i] = [];

                array_push($this->registeredUsersPerEvent[$i], $userId);
            }
        }

//       var_dump ( $eventsGuests);

        return $eventsGuests;
    }

    private function getRandomizedInvitations()
    {
        $invitations = [];

        for ($i = 1; $i <= $this->eventsCounter; $i++) {
            for ($j = 0; $j < rand(0, 5); $j++) {
                $userId = $this->randomUserId();
                while (in_array($userId, $this->registeredUsersPerEvent[$i])) {
                    $userId = $this->randomUserId();
                }
                $invitations[] = ['event_id' => $i, 'user_id' => $userId];

                if (!array_key_exists($i, $this->registeredUsersPerEvent))
                    $this->registeredUsersPerEvent[$i] = [];

                array_push($this->registeredUsersPerEvent[$i], $userId);
            }
        }

        return $invitations;
    }

    private function randomEventId()
    {
        return rand(1, $this->eventsCounter);
    }

    private function randomUserId()
    {
        return rand(1, $this->usersCounter);
    }
}
