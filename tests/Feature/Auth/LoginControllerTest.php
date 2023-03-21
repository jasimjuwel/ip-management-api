<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LoginControllerTest extends TestCase
{
    public function testRequireEmailAndLogin()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'status' => false,
                'code' => 422,
                'message' => "Input Data Not Valid",
                'data' => null,
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ],
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ]);

    }

    public function testUserLoginSuccessfully()
    {
        $user = ['email' => 'test@example.com', 'password' => 'password'];
        $this->json('POST', 'api/login', $user)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'name'
                ]
            ]);
    }
}
