<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => env('APP_API_VERSION', ''),
    'namespace' => '\App\Api\V1\Controllers'
], function ($router) {
    /**
     * PUBLIC ACCESS
     */
//    $router->group([], function ($router) {
//    });

    // $router->get('subscribers', 'SubscriberController@index');
    // $router->post('subscriptions-messages', 'WaitingListMsController@store');

    /**
     * USER APPLICATION PRIVATE ACCESS
     */
    $router->group([
        'prefix' => 'user',
        'namespace' => 'Application',
        'middleware' => [
            'checkUser',
        ],
    ], function ($router) {
        /**
         * Dashboard
         */
        $router->get('/dashboard', 'DashboardController@index');
        $router->get('/dashboard/balance-summary', 'DashboardController@getEarningsOverview');
    });

    /**
     * ADMIN PANEL ACCESS
     */
    $router->group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => [
            'checkUser',
            'checkAdmin'
        ]
    ], function ($router) {
        /**
         * Dashboard
         */
        $router->get('/dashboard', 'DashboardController@index');

        /**
         * Subscribers
         */
        $router->get('subscribers', 'SubscriberController@index');
        $router->post('subscribers', 'SubscriberController@store');
        $router->get('subscribers/{id}', 'SubscriberController@show');
        $router->put('subscribers/{id}', 'SubscriberController@update');
        $router->delete('subscribers/{id}', 'SubscriberController@destroy');

        /**
         * Administrators
         */
        $router->get('administrators', 'AdminController@index');
        $router->post('administrators', 'AdminController@store');
        $router->get('administrators/{id}', 'AdminController@show');
        $router->put('administrators/{id}', 'AdminController@update');
        $router->delete('administrators/{id}', 'AdminController@destroy');
        $router->patch('administrators/{id}', 'AdminController@updateRole');

        /**
         * Waiting List Messages
         */
        $router->get('subscriptions-messages', 'WaitingListMSController@index');
        $router->post('subscriptions-messages', 'WaitingListMSController@store');
    });
});
