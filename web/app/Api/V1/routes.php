<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => env('APP_API_VERSION', ''),
    'namespace' => '\App\Api\V1\Controllers',
], function ($router) {
    /**
     * PUBLIC ACCESS
     */

    /**
     * PRIVATE ACCESS
     */
    $router->group([
        'middleware' => 'checkUser',
    ], function ($router) {
    });

    /**
     * ADMIN PANEL ACCESS
     */
    $router->group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => [
            'checkUser',
            'checkAdmin',
        ],
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
    });
});
