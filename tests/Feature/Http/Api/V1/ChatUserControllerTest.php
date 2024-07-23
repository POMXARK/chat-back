<?php

namespace Tests\Feature\Http\Api\V1;

use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/**
 * Тесты контроллера для работы c связями между чатами и пользователями.
 *
 * @see ChatUserController
 */
#[Group('ChatUserController')]
final class ChatUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ошибка, если запрос выполняется неавторизованным пользователем.
     */
    public function testAuthError(): void
    {
        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);

        $this->get(route('api.v1.chat-user.index'));
        $this->post(route('api.v1.chat-user.store'));
    }

    /**
     * Успешное получения списка чатов авторизованного пользователя.
     */
    public function testIndexSuccess(): void
    {
        $countMessage = 1;
        $userId = rand(0, 99999);
        $chatId = 1;
        $chatUsersId = rand(0, 99999);
        Sanctum::actingAs(User::factory()->create(['id' => $userId]));
        User::factory()->create();
        Chat::factory()->create(['id' => $chatId]);
        ChatUsers::factory()->create(['id'=> $chatUsersId, 'chat_id' => $chatId, 'user_id' => $userId]);
        ChatUsers::factory()->create();
        $params = ['user_id' => $userId];

        $response = $this->get(route('api.v1.chat-user.index', $params), headers: ['Accept' => 'application/json']);
        $models = $response->getOriginalContent();

        $this->assertCount($countMessage, $models);
        $this->assertSame(LengthAwarePaginator::class, get_class($models));
        foreach ($models as $model) {
            $this->assertSame(Chat::class, get_class($model));
        }
        $response->assertOk();
    }

    /**
     * Успешное создание связи или возвращение существующей.
     */
    public function testStoreSuccess(): void
    {
        $countMessage = 1;
        $fromUserId = rand(0, 99999);
        $forUserId = rand(0, 99999);
        Sanctum::actingAs(User::factory()->create(['id' => $fromUserId]));
        User::factory()->create(['id' => $forUserId]);
        $params = ['user_id' => $forUserId];

        $this->post(
            route('api.v1.chat-user.store', ['user_id' => $fromUserId]),
            headers: ['Accept' => 'application/json']
        );
        $this->post(route('api.v1.chat-user.store', $params), headers: ['Accept' => 'application/json']);
        $response = $this->post(route('api.v1.chat-user.store', $params), headers: ['Accept' => 'application/json']);

        $models = $response->getOriginalContent()['chat'];
        $chatId = $models->keys()[0];
        $this->assertCount($countMessage, $models);
        $this->assertSame(Collection::class, get_class($models));
        foreach ($models as $subModels) {
            $this->assertSame(Collection::class, get_class($subModels));
            foreach ($subModels as $model) {
                $this->assertSame(ChatUsers::class, get_class($model));
                $this->assertTrue($model->user_id == $fromUserId || $forUserId);
                $this->assertEquals($chatId, $model->chat_id);
            }
        }
        $response->assertOk();
    }
}
