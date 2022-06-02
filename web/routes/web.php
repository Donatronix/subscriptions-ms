<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Jobs\PingJob;
use App\Jobs\subscriberJobs;
use App\Models\Subscriber;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => env('APP_API_PREFIX', '')
], function ($router) {
    include base_path('app/Api/V1/routes.php');
});

/*-------------------------
   T E S T S  Routes
-------------------------- */
$router->group([
    'prefix' => env('APP_API_PREFIX', '') . '/tests'
], function ($router) {
    $router->get('db-test', function () {
        if (DB::connection()->getDatabaseName()) {
            echo "Connected successfully to database: " . DB::connection()->getDatabaseName();
        }
    });

    $router->get('/test', function () use ($router) {
        $lists = Subscriber::all();
        // $data = "Testingg communication messages!";
        print_r("Msg has been sent!");
        $proLink ="https://discord.gg/DUMwfyckKy"; 
        dispatch(new subscriberJobs($lists, $proLink))->onQueue('waitingLinst');
        // return "Msg has been sent!";
    });
    
});

