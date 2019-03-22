<?php

use Illuminate\Database\Seeder;
use App\Libs\Utilities\Random;

class EventsTableSeeder extends Seeder
{

    private $counter = 0;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [];
        for ($i = 0; $i < 1500; $i++) {
            $startAt = Random::date('2018-01-01 12:01', '2020-01-01 00:01');
            $endAt = Random::date($startAt, '2020-01-01 23:59');
            $closeAt = Random::date('2018-01-01 00:01', $endAt);
            $events[] = [
                'name' => $this->randomName(),
                'description' => $this->getDescription(),
                'maxGuests' => Random::int(1),
                'starts_at' => $startAt,
                'ends_at' => $endAt,
                'closes_at' => $closeAt,
                'street' => 'none 0/0',
                'city' => 'unknown',
                'zipCode' => '00-000',
                'latitude' => Random::float(49, 55),
                'longitude' => Random::float(14, 24),
                'private' => $i%10 === 0,
            ];
//            \App\Event::create();
        }
        DB::table('events')->insert($events);
    }

    private function randomName() {
        $names = ["nazwa testowa", "FYRE festival", "woodstock", "opener", "Burning Man", "Carnivale", "Snowbombing"];
       return $names[array_rand($names)] . '-' . (++$this->counter);
    }

    private function getDescription() {
        return "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    }
}
