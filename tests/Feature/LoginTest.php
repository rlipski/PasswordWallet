<?php
// tests/Feature/PasswordTest.php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Auth;

class LoginTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function test_user_can_view_a_login_form()
  {
    $response = $this->get('/login');

    $response->assertSuccessful();
    $response->assertViewIs('auth.login');
  }

  /** @test */
  public function test_user_can_login()
  {

    $user = User::factory()->create([
        'name' => 'Test',
        'email' => 'test@gmail.com',
        'password' => hash_hmac('sha512', 'qwerty', 'random key'),
        'is_password_kept_as_hash' => '0',
    ]);

    $response = $this->post('/login', [
        'password' => 'qwerty',
        'email' => 'test@gmail.com',
    ]);

    $response->assertRedirect('home');
  }

  /** @test */
  public function test_user_login_attempt_pass()
  {

    $user = User::factory()->create([
        'name' => 'Test',
        'email' => 'test@gmail.com',
        'password' => hash_hmac('sha512', 'qwerty', 'random key'),
        'is_password_kept_as_hash' => '0',
    ]);

    $response = $this->post('/login', [
        'password' => 'qwerty',
        'email' => 'test@gmail.com',
    ]);

    Auth::login($user);
    $this->be($user);

    $this->assertDatabaseHas('login_attempts', [
        'user_id' => $user->id,
        'created_at' => now(),
        'is_login_correct' => '1'
    ]);
  }

  /** @test */
  public function test_user_login_attempt_fail()
  {

    $user = User::factory()->create([
        'name' => 'Test',
        'email' => 'test@gmail.com',
        'password' => hash_hmac('sha512', 'qwerty', 'random key'),
        'is_password_kept_as_hash' => '0',
    ]);

    $response = $this->post('/login', [
        'password' => 'wrongpassword',
        'email' => 'test@gmail.com',
    ]);

    Auth::login($user);
    $this->be($user);

    $this->assertDatabaseHas('login_attempts', [
        'user_id' => $user->id,
        'created_at' => now(),
        'is_login_correct' => '0'
    ]);
  }

}