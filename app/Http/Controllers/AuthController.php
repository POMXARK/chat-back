<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\UseCases\Auth\AuthCommandSignIn;
use App\UseCases\Auth\AuthCommandSignUp;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Tests\Feature\Http\AuthControllerTest;

/**
 * @see AuthControllerTest
 */
#[Unauthenticated]
class AuthController extends Controller
{
    /**
     * Регистрация пользователя.
     */
    #[BodyParam("password_confirmation", "string", example: 'No-example')]
    public function register(RegisterUserRequest $request, AuthCommandSignUp $commandSignUp): JsonResponse
    {
        return response()->json(...$commandSignUp->handle($request->validated()));
    }

    /**
     * Авторизация пользователя.
     */
    public function login(LoginUserRequest $request, AuthCommandSignIn $commandSignIn): JsonResponse
    {
        return response()->json(...$commandSignIn->handle($request->validated()));
    }
}
