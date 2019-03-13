<?php

use Illuminate\Database\Seeder;

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
        for ($i = 0; $i < 150; $i++) {
            $startAt = $this->randomDate('1991-12-21 12:01', '2121-12-21 00:01');
            $endAt = $this->randomDate($startAt, '2121-12-21 23:59');
            $closeAt = $this->randomDate('1991-12-21 00:01', $endAt);
            $events[] = [
                'name' => $this->randomName(),
                'description' => $this->getDescription(),
                'maxGuests' => $this->randomInt(),
                'starts_at' => $startAt,
                'ends_at' => $endAt,
                'closes_at' => $closeAt,
                'street' => 'none 0/0',
                'city' => 'unknown',
                'zipCode' => '00-000',
                'latitude' => $this->randomFloat(49, 55),
                'longitude' => $this->randomFloat(14, 24),
                'private' => $i%10 === 0,
            ];
//            \App\Event::create();
        }
        DB::table('events')->insert($events);
    }

    private function randomName() {
       return array_rand(["nazwa testowa", "FYRE festival", "woodstock", "opener", "Burning Man", "Carnivale", "Snowbombing"]) . (++$this->counter);
    }

    private function randomInt(int $min = 0, int $max = 100) {
        return rand($min, $max);
    }

    private function getDescription() {
        return "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    }

    // dates format Y-m-d H:i
    private function randomDate(string $startDate, string $endDate) {
        {
            // Convert to timetamps
            $min = strtotime($startDate);
            $max = strtotime($endDate);

            // Generate random number using above bounds
            $val = rand($min, $max);

            // Convert back to desired date format
            return date('Y-m-d H:i', $val);
        }
    }

    private function randomFloat(int $min = 0, int $max = 100) {
        return rand($min, $max-1) + (rand(0, 1000)/1000);
    }
}
