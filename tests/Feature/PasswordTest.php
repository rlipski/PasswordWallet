<?php
// tests/Feature/PasswordTest.php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\Password\PasswordController;
use Auth;
class PasswordTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function test_user_can_not_view_a_add_password_form()
  {
    $response = $this->get('/password/create');
    $response->assertRedirect('login');
  }

  /** @test */
  public function test_user_can_view_a_add_password_form()
  {
    $user = User::factory()->create([
        'name' => 'Test',
        'email' => 'test@gmail.com',
        'password' => hash_hmac('sha512', 'qwerty', 'random key'),
        'is_password_kept_as_hash' => '0',
    ]);
    $this->be($user);
    $response = $this->actingAs($user)->get('/password/create');

    $response->assertViewIs('password.create');
  }

  /** @test */
  public function test_password_can_be_created()
  {
    $user = User::factory()->create([
        'name' => 'Test',
        'email' => 'test@gmail.com',
        'password' => hash_hmac('sha512', 'qwerty', 'random key'),
        'is_password_kept_as_hash' => '0',
    ]);
    Auth::login($user);
    $this->be($user);
    $response = $this->actingAs($user)->post('/password/create', [
        'password' => 'qwerty',
        'login' => 'login',
        'web_address' => 'http://google.pl',
        'description' => 'description',
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('passwords', [
        'password' => PasswordController::encrypt('qwerty'),
        'login' => 'login',
        'web_address' => 'http://google.pl',
        'description' => 'description',
    ]);
  }
}