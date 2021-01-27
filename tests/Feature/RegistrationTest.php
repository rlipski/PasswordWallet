<?php
// tests/Feature/PasswordTest.php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function test_user_can_view_a_register_form()
  {
    $response = $this->get('/register');

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
  }

  /** @test */
  public function test_user_can_register()
  {
     $this->post('/register', [
        'password' => 'qwerty',
        'password_confirmation' => 'qwerty',
        'email' => 'test@gmail.com',
        'isPasswordKeptAsHash' => '1',
        'name' => 'testUser',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@gmail.com',
        'is_password_kept_as_hash' => '1',
        'name' => 'testUser',
    ]);
  }

}