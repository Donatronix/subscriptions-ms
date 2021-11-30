<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => 'waiting-lists',
    'namespace' => '\App\Api\V1\Controllers'
], function ($router) {

    $router->group(
        [
            'middleware' => 'checkUser'
        ],
        function ($router) {


            /**
             * ADMIN PANEL
             */
            $router->group([
                'prefix' => 'admin',
                'namespace' => 'Admin',
                'middleware' => 'checkAdmin'
            ], function ($router) {
                /**
                 * User admin
                 */
                $router->get('/users/{id}', 'UserController@show');
            });
        }
    );
});
