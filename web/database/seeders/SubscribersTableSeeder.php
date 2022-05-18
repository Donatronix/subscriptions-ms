<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Exception;
use Illuminate\Database\Seeder;

//use MongoDB\Driver\Monitoring\Subscriber;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        Subscriber::factory()->create([
            'id' => '00000000-1000-1000-1000-000000000000',
        ]);

        Subscriber::factory()->create([
            'id' => '00000000-2000-2000-2000-000000000000',
        ]);

        // Other Subscribers
        Subscriber::factory()->count(10)->create();
    }
}
