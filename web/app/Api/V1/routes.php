<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => 'files',
    'namespace' => '\App\Api\V1\Controllers',
   // 'middleware' => 'checkUser'
], function ($router) {
    $router->get('/', 'FileController@index');
    $router->post('/', 'FileController@store');
    $router->patch('/', 'FileController@update');
    $router->get('/{id}', 'FileController@show');
    $router->delete('/{id}', 'FileController@destroy');
});
