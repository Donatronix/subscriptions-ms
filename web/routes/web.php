<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Jobs\subscriberJobs;
use App\Listeners\WaitingListMS as ListenersWaitingListMS;
use App\Models\SubMgsId;
use App\Models\Subscriber;
use App\Models\SubscriberMessage;
use App\Models\WaitingListMS;
use Sumra\SDK\PubSub;
use Illuminate\Support\Facades\Route;
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

    //Simple test for view for waiting list messae
    $router->get('/view', function () use ($router) {
        return view('test');
    });
    $router->post('/analyze', 'testController@storeTest');
    
});