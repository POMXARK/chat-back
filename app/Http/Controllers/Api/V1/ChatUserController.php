<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\UseCases\Chat\ChatCommandCreate;
use App\UseCases\Chat\ChatCommandGetAll;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\QueryParam;
use Tests\Feature\Http\Api\V1\ChatUserControllerTest;

/**
 * Контроллер связи между чатом и пользователем.
 *
 * @see ChatUserControllerTest
 */
class ChatUserController extends Controller
{
    /**
     * Получить все чаты авторизованного пользователя постранично.
     */
    #[QueryParam("page", "int", required: false, example: 1)]
    public function index(ChatCommandGetAll $chatCommandGetAll): JsonResponse
    {
        return response()->json($chatCommandGetAll->handle());
    }

    /**
     * Создать или получить чат с пользователем.
     */
    #[QueryParam("user_id", "int")]
    public function store(StoreUserRequest $request, ChatCommandCreate $chatCommandCreate): JsonResponse
    {
        return response()->json($chatCommandCreate->handle($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
