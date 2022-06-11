<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Anik\Amqp\ConsumableMessage;
use Anik\Laravel\Amqp\Facades\Amqp;
use App\Models\Subscriber;
use App\Models\SubscriberMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
    'prefix' => env('APP_API_PREFIX', '') . '/v1/tests'
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

    $router->post('/analyze', 'WaitingListMSController@waitingListMessage');
    $router->post('/waitlist/messages', WaitingListMSController::class, 'store');
});
