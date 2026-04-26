<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $phone = '09123456789';
        \Illuminate\Support\Facades\Cache::put('verified_phone_' . $phone, true, 600);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'username' => 'testuser',
            'phone' => $phone,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.landing', absolute: false));
    }
}
