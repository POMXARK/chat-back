<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\User;
use Knuckles\Scribe\Attributes\QueryParam;

class ChatUserController extends Controller
{
    const COUNT_CHATS = 20;

    /**
     * Display a listing of the resource.
     */
    #[QueryParam("page", "int", required: false, example: 1)]
    public function index()
    {
        $chats = Chat::query()->withWhereHas('users', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->paginate(self::COUNT_CHATS);

        return response()->json($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[QueryParam("user_id", "int")]
    public function store(StoreUserRequest $request)
    {
        $chatUsers = ChatUsers::query()
            ->where('user_id', auth()->user()->id)
            ->orWhere('user_id', $request->input('user_id'))
            ->get();


        $chats =  ($chatUsers->groupBy('chat_id'))->filter(function ($items) use ($request) {
            $existFromUser = false;
            $existForUser = false;

            foreach ($items as &$item) {
                if ($item->user_id == auth()->user()->id) {
                    $existFromUser = true;
                }
                if ($item->user_id == $request->input('user_id')) {
                    $existForUser = true;
                }
            }
            if ($existFromUser & $existForUser) {
                return true;
            }
            return false;
        });

        if (count($chats) > 0) {
            return response()->json([
                'chat' => $chats,
                'success' => false,
            ]);
        }

        $fromUser = User::query()->findOrFail(auth()->user()->id);
        $forUser = User::query()->findOrFail($request->input('user_id'));

        $chat = Chat::query()->create();

        ChatUsers::query()->create([
            'user_id' => $fromUser->id,
            'chat_id' => $chat->id,
        ]);

        ChatUsers::query()->create([
            'user_id' => $forUser->id,
            'chat_id' => $chat->id,
        ]);

        return response()->json([
            'chat' => $chat,
            'success' => true,
        ]);
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
