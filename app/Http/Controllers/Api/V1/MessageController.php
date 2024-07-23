<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Chat;
use App\UseCases\Message\MessageCommandCreate;
use App\UseCases\Message\MessageCommandGetAll;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\QueryParam;
use Tests\Feature\Http\Api\V1\MessageControllerTest;

/**
 * Контроллер сообщений.
 *
 * @see MessageControllerTest
 */
class MessageController extends Controller
{
    /**
     * Получить все сообщения из чатов авторизованного пользователя постранично.
     */
    #[QueryParam("page", "int", required: false, example: 1)]
    public function index(Chat $chat, MessageCommandGetAll $commandGetAll): JsonResponse
    {
        return response()->json($commandGetAll->handle($chat));
    }

    /**
     * Создать сообщение и отправить событие.
     */
    public function store(Chat $chat, StoreMessageRequest $request, MessageCommandCreate $messageCommandCreate): JsonResponse
    {
        return response()->json($messageCommandCreate->handle($chat, $request->validated()));
    }
}
