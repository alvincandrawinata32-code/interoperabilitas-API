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

    public function test_user_dapat_register()
    {
        //Arrange
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //Act
        $response = $this->post('/api/auth/register', $userData);

        //Assert
        $response->assertStatus(201);
    }

    public function test_register_gagal_jika_email_sudah_terdaftar()
    {
        //Arrange
        $existingUser = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //Act
        $response = $this->post('/api/auth/register', $userData);

        //Assert
        $response->assertStatus(422);
    }

    public function test_register_gagal_jika_password_kurang_dari_8_karakter()
    {
        //Arrange
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        //Act
        $response = $this->post('/api/auth/register', $userData);

        //Assert
        $response->assertStatus(422);
    }

    public function test_user_dapat_logout()
    {
        //Arrange
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        //Act
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->post('/api/auth/logout');

        //Assert
        $response->assertStatus(200);
    }
}