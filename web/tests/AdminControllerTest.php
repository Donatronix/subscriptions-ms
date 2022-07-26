<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Support\Str;
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
            ->seeJson(['type' => 'success'])
            ->response->getContent();

    }

    public function testAdminControllerShow()
    {
        $response = $this->get('v1/admin/administrators/96ced834-1c8f-4dd5-885c-8084d0680ebd')
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();


    }

    public function testAdminControllerStore()
    {
        $response = $this->post('v1/admin/administrators', [
            'name' => Str::random(6),
            'email' => Str::random(6) . '@mail.com',
            'phone' => Str::random(6),
        ])
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();


    }

    public function testAdminControllerUpdate()
    {
        $response = $this->put('v1/admin/administrators/96cee9ef-9336-493d-9923-e3f67d9fdf77', [
            'name' => 'Donald Blessing',
            'email' => 'donald@mail.com',
            'phone' => '08065302534',
        ])
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();


    }

    public function testAdminControllerDestroy()
    {
        $response = $this->delete('v1/admin/administrators/' . Admin::query()->whereNull('deleted_at')->orderBy('created_at', 'desc')->first()->id)
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();
    }

    protected array $roles = [
        'admin',
        'super admin',
    ];

    public function testAdminControllerUpdateRole()
    {
        $role = $this->roles[rand(0, count($this->roles) - 1)];
        
        $id = Admin::query()
            ->where('role', '!=', $role)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->first()
            ->id;

        $response = $this->patch('v1/admin/administrators/' . $id, [
            'role' => $role,
        ])
            ->seeStatusCode(200)
            ->seeJson(['type' => 'success'])
            ->response->getContent();


    }
}
