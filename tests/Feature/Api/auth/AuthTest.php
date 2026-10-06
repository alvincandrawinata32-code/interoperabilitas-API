<?php

namespace Tests\Feature\Api\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\Auth\LoginController;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_dapat_login()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        //Act
        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        //Assert
        $response->assertStatus(200);
    }

    public function test_user_gagal_login_dengan_kredensial_salah()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        //Act
        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        //Assert
        $response->assertStatus(401);
    }

    public function test_login_gagal_jika_email_kosong()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        //Act
        $response = $this->postJson('/api/auth/login', [
            'email' => '',
            'password' => 'password',
        ]);

        //Assert
        $response->assertStatus(422);
    }

    public function test_login_gagal_jika_password_kosong()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        //Act
        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => '',
        ]);

        //Assert
        $response->assertStatus(422);
    }

    public function test_login_gagal_jika_format_email_salah()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        //Act
        $response = $this->post('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        //Assert
        $response->assertStatus(422);
    }

    public function test_login_gagal_jika_email_tidak_terdaftar()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        //Act
        $response = $this->post('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        //Assert
        $response->assertStatus(401);
    }
}