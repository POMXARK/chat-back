<?php

namespace Tests\Feature\Http;

use App\Http\Controllers\AuthController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Тесты контроллера авторизации.
 *
 * @see AuthController
 */
#[Group('AuthController')]
final class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Успешное создание пользователя.
     */
    public function testRegisterSuccess(): void
    {
        $password = Hash::make(fake()->name());
        $params = [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post(route('register', $params), headers: ['Accept' => 'application/json']);

        $this->assertSame(UserResource::class, get_class($response->getOriginalContent()['user']));
        $this->assertArrayHasKey('token', $response->getOriginalContent());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Успешная авторизация пользователя.
     */
    public function testLoginSuccess(): void
    {
        $password = fake()->name();
        /** @var User $user */
        $user = Sanctum::actingAs(User::factory()->create(['password' => Hash::make($password)]));
        $params = [
          'email' => $user->email,
          'password' => $password,
        ];

        $response = $this->post(route('login', $params), headers: ['Accept' => 'application/json']);

        $this->assertSame(UserResource::class, get_class($response->getOriginalContent()['user']));
        $this->assertArrayHasKey('token', $response->getOriginalContent());
        $response->assertOk();
    }
}
