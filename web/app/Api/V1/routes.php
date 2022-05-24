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
    $router->group([
        'prefix' => '',
        'namespace' => 'Admin',
    ], function ($router) {
        /**
         * User admin
         */
        $router->post('/subscribers', 'SubscriberController@store');
        $router->put('/subscribers/{id}', 'SubscriberController@update');
    });

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
         * Subscribers
         */
        $router->get('/subscribers', 'SubscriberController@index');
        $router->get('/subscribers/{id}', 'SubscriberController@show');
        $router->post('/subscribers', 'SubscriberController@store');
        $router->put('/subscribers/{id}', 'SubscriberController@update');
        $router->delete('/subscribers/{id}', 'SubscriberController@destroy');

    });

    /**
     * ADMIN PANEL ACCESS
     */
    $router->group([
        'prefix' => 'admins',
        'namespace' => 'Admin',
        'middleware' => [
            'checkUser',
            'checkAdmin',
        ],
    ], function ($router) {

        /**
         * Admins
         */
        $router->get('/', 'AdminController@index');
        $router->get('/{id}', 'AdminController@show');
        $router->post('/', 'AdminController@store');
        $router->put('/{id}', 'AdminController@update');
        $router->delete('/{id}', 'AdminController@destroy');
        $router->patch('/{id}', 'AdminController@updateRole');


        $router->get('/dashboard', 'SubscribersDashboardController@index');
    });
});
