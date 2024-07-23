<?php

namespace Tests\Feature\Http\Api\V1;

use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/**
 * Тесты контроллера для работы с сообщениями.
 *
 * @see MessageController
 */
#[Group('MessageController')]
final class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ошибка, если запрос выполняется неавторизованным пользователем.
     */
    public function testAuthError(): void
    {
        $params = ['chat' => 1, 'text' => fake()->text];

        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);

        $this->post(route('api.v1.message', $params));
        $this->get(route('api.v1.messages'));
    }

    /**
     * Ошибка отправки события при выключенном websocket сервере после создание сообщения.
     */
    public function testCreateMessageError(): void
    {
        $userId = rand(0, 99999);
        $chatId = 1;
        $chatUsersId = rand(0, 99999);
        Sanctum::actingAs(User::factory()->create(['id' => $userId]));
        $chat = Chat::factory()->create(['id' => $chatId]);
        ChatUsers::factory()->create(['id'=> $chatUsersId, 'chat_id' => $chatId, 'user_id' => $userId]);
        $params = ['chat' => $chat, 'text' => fake()->text];

        $this->withoutExceptionHandling();
        $this->expectException(BroadcastException::class);
        $response = $this->post(route('api.v1.message', $params), headers: ['Accept' => 'application/json']);

        $response->assertOk();
    }

    /**
     * Успешное получение сообщений.
     */
    public function testGetMessagesEmptySuccess(): void
    {
        $countMessage = 2;
        $userId = rand(0, 99999);
        $chatId = 1;
        $chatUsersId = rand(0, 99999);
        Sanctum::actingAs(User::factory()->create(['id' => $userId]));
        Chat::factory()->create(['id' => $chatId]);
        ChatUsers::factory()->create(['id'=> $chatUsersId, 'chat_id' => $chatId, 'user_id' => $userId]);
        Message::factory($countMessage)->create(['chat_id' => $chatId, 'user_id' => $userId]);
        $params = ['chat' => $chatId];

        $response = $this->get(route('api.v1.messages', $params), headers: ['Accept' => 'application/json']);
        $models = $response->getOriginalContent();

        $this->assertCount($countMessage, $models);
        $this->assertSame(MessageCollection::class, get_class($models));
        foreach ($models as $model) {
            $this->assertSame(MessageResource::class, get_class($model));
        }
        $response->assertOk();
    }
}
