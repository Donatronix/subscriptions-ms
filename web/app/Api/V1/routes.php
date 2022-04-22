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
     * PRIVATE ACCESS
     */
    $router->group([
        'middleware' => 'checkUser'
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
            'checkAdmin'
        ]
    ], function ($router) {
        /**
         * User admin
         */
        $router->get('/users', 'UserController@index');
        $router->get('/users/{id}', 'UserController@show');
    });
});
