<?php

namespace Tests;

use Laravel\Lumen\Testing\WithoutMiddleware;

class AdminControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdminControllerIndex()
    {
        $response = $this->get('v1/admin/administrators')
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success']);
//        dd($response->response->getContent());

    }
}
