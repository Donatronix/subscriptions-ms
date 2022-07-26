<?php

namespace Database\Seeders;

use App\Models\Admin;
use Exception;
use Illuminate\Database\Seeder;

//use MongoDB\Driver\Monitoring\Subscriber;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        // Add admins
        Admin::factory()->count(20)->create();
    }
}
