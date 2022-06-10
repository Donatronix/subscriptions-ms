<?php

namespace Database\Seeders;

use App\Models\WaitingListMS;
use Illuminate\Database\Seeder;

class Messages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        WaitingListMS::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 20; $i++) {
            WaitingListMS::create([
                'message' => $faker->text($maxNbChars = 300),
            ]);
        }
    }
}
