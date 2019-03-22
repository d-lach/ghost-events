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

        DB::table('events_hosts')->insert($hosts);
        DB::table('events_guests')->insert($hosts); // hosts are also guests by default

        DB::insert("insert into `events_guests` (`event_id`, `user_id`) values "
            . implode(", ", $guests)
            . " on duplicate key update event_id = event_id");

        $this->validateGuestsNumber();
    }

    private function validateGuestsNumber()
    {
        DB::raw('UPDATE events, (SELECT events.*, count(events.id) as numberOfGuests FROM `events` left join `events_guests` on events.id = events_guests.event_id group by events.id having maxGuests < numberOfGuests) as invalidEvents set events.maxGuests = invalidEvents.numberOfGuests where events.id = invalidEvents.id');
        /*Event::select('events.*', DB::raw('count(events.id) as numberOfGuests'))
            ->join('events_guests', 'events.id', '=', 'events_guests.event_id', 'left')
            ->groupBy('events.id')
            ->having('count(events.id)', '>', 'events.maxGuests')
            ->update(['events.maxGuests' => DB::raw('`numberOfGuests`')]);*/
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
                $eventsGuests[] = "(" . $i . "," . $userId . ")";

                if (!array_key_exists($i, $this->registeredUsersPerEvent))
                    $this->registeredUsersPerEvent[$i] = [];

                array_push($this->registeredUsersPerEvent[$i], $userId);
            }
        }

        return $eventsGuests;
    }

    private function randomUserId()
    {
        return rand(1, $this->usersCounter);
    }
}
