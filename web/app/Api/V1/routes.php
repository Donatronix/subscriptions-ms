<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => 'waiting-lists',
    'namespace' => '\App\Api\V1\Controllers',
    'middleware' => 'checkUser'
], function ($router) {
    $router->get('/', 'UserController@index');
});
