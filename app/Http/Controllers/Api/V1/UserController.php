<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UseCases\User\UserCommandGetAll;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\QueryParam;
use Tests\Feature\Http\Api\V1\UserControllerTest;

/**
 * Контроллер пользователей.
 *
 * @see UserControllerTest
 */
class UserController extends Controller
{
    /**
     * Получить всех пользователей постранично.
     */
    #[QueryParam("page", "int", required: false, example: 1)]
    public function index(UserCommandGetAll $commandGetAll): JsonResponse
    {
        return response()->json(...$commandGetAll->handle());
    }
}
