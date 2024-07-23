<?php

namespace Tests\Feature\Http\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/**
 * Тесты контроллера для работы c пользователями.
 *
 * @see UserController
 */
#[Group('UserController')]
final class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ошибка, если запрос выполняется неавторизованным пользователем.
     */
    public function testAuthError(): void
    {
        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);

        $this->get(route('api.v1.users'));
    }

    /**
     * Успешное получения списка пользователей.
     */
    public function testIndexSuccess(): void
    {
        $countUsers = 2;
        Sanctum::actingAs(User::factory()->create());
        Sanctum::actingAs(User::factory()->create());

        $response = $this->get(route('api.v1.users'), headers: ['Accept' => 'application/json']);
        $models = $response->getOriginalContent()['users'];

        $this->assertNotEmpty($models);
        $this->assertCount($countUsers, $models);
        $this->assertSame(AnonymousResourceCollection::class, get_class($models));
        foreach ($models as $model) {
            $this->assertSame(UserResource::class, get_class($model));
        }
        $response->assertOk();
    }
}
