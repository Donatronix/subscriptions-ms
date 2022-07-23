<?php

namespace Tests;

use Laravel\Lumen\Testing\WithoutMiddleware;

class AdminSubscriberControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function testAdminSubscriberControllerIndex()
    {
        $response = $this->get('v1/admin/subscribers')
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();
    }

    public function testAdminSubscriberControllerShow()
    {
        $response = $this->get('v1/admin/subscribers/96ced833-dbf0-41fe-bff7-645f03ccb81c')
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();

        dd($response);
    }
}
