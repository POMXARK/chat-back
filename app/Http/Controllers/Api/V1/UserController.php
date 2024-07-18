<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Knuckles\Scribe\Attributes\QueryParam;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    const COUNT_USERS = 20;

    #[QueryParam("page", "int", required: false, example: 1)]
    public function index()
    {
        return response([
            'users' => UserResource::collection(
                User::query()
                    ->select(['id', 'email', 'last_name', 'first_name'])
                    ->paginate(self::COUNT_USERS)
            ),
           ], Response::HTTP_OK);
    }
}
