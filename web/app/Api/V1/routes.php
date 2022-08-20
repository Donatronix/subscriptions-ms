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
     *
     * level with free access to the endpoint
     */
    $router->group([
        'namespace' => 'Public'
    ], function ($router) {
        $router->post('/waitlist/messages', 'WaitingListMSController@store');
        $router->post('/analyze', 'WaitingListMSController@waitingListMessage');
    });

    /**
     * USER APPLICATION PRIVATE ACCESS
     *
     * Application level for users
     */
    $router->group([
        'prefix' => 'app',
        'namespace' => 'Application',
        'middleware' => 'checkUser'
    ], function ($router) {
        /**
         * Dashboard
         */
        $router->get('/dashboard', 'DashboardController@index');
        $router->get('/dashboard/balance-summary', 'DashboardController@getEarningsOverview');
    });

    /**
     * ADMIN PANEL ACCESS
     *
     * Admin / super admin access level (E.g CEO company)
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

    /**
     * WEBHOOKS
     *
     * Access level of external / internal software services
     */
    $router->group([
        'prefix' => 'webhooks',
        'namespace' => 'Webhooks'
    ], function ($router) {
        //
    });
});
