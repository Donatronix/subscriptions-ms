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
    // $router->get('subscribers', 'SubscriberController@index');
    // $router->post('waiting-list-messages', 'WaitingListMsController@store');
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
         * Admins
         */
        $router->get('admins', 'AdminController@index');
        $router->post('admins', 'AdminController@store');
        $router->get('admins/{id}', 'AdminController@show');
        $router->put('admins/{id}', 'AdminController@update');
        $router->delete('admins/{id}', 'AdminController@destroy');
        $router->patch('admins/{id}', 'AdminController@updateRole');

        /**
         * Waiting List Messages
         */
        $router->get('waiting-list-messages', 'WaitingListMsController@index');
        $router->post('waiting-list-messages', 'WaitingListMsController@store');
    });
});
