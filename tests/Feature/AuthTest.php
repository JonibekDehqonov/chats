<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ro‘yxatdan o‘tish testini yaratish
     *
     * @return void
     */
    public function test_user_can_register()
    {

        $userData = [
            'name' => 'Test User',
            'email' => 'testuser1@example.com',
            'mobile_number'=>'1111',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

   
        $response = $this->postJson('/api/register', $userData);

        
        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User registered successfully'
                 ]);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        
        User::create([
            'name' => 'Test User',
            'email' => 'testuser1@example.com',
            'mobile_number'=>'1111',
            'password' => bcrypt('password123')
        ]);

        $userData = [
            'name' => 'Test User 2',
            'email' => 'testuser1@example.com',
            'mobile_number'=>'1111',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Email already exists'
                 ]);
    }
}
