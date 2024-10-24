<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_process_success()
    {
        $response = $this->post('/api/login_process', [
            'username' => 'superadmin',
            'password' => 'abc123'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Login successful',
            'notify' => true,
        ]);

        $response->assertJsonStructure([
            'token',
        ]);
    }

    public function test_login_process_invalid_credentials()
    {
        $response = $this->post('/api/login_process', [
            'username' => 'superadmin',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid username or password',
            'notify' => true,
        ]);
    }

    public function test_login_process_validation_error()
    {
        $response = $this->post('/api/login_process', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Validation failed',
            'notify' => true,
        ]);

        $response->assertJsonStructure([
            'validate_errors' => [
                'username',
                'password'
            ]
        ]);
    }
}

