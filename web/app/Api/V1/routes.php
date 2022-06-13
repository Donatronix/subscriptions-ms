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

    /**
     * USER APPLICATION PRIVATE ACCESS
     */
    $router->group([
        'prefix' => 'user',
        'namespace' => 'User',
        'middleware' => [
            'checkUser',
        ],
    ], function ($router) {
        /**
         * Dashboard
         */
        $router->get('/dashboard', 'DashboardController@index');
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
        $router->get('waiting-list-messages', 'WaitingListMSController@index');
        $router->post('waiting-list-messages', 'WaitingListMSController@store');
    });
});
