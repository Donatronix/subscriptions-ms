<?php

namespace Tests;

use Laravel\Lumen\Testing\WithoutMiddleware;

class AdminDashboardControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdminDashboardControllerIndex()
    {
        $response = $this->get('v1/admin/dashboard')
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();

    }
}
