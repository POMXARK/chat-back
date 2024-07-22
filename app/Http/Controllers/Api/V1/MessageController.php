<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\UserResource;
use App\Jobs\SendMessage;
use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\QueryParam;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    const COUNT_MESSAGES = 20;

    public function index()
    {
        $user = User::query()->where('id', auth()->id())->select([
            'id', 'first_name', 'email',
        ])->first();

        return response([
            'user' => UserResource::make($user),
        ]);
    }

    #[QueryParam("page", "int", required: false, example: 1)]
    public function messages(Chat $chat): JsonResponse
    {
        $messages = Message::query()->with('user')->withWhereHas('chat.users', function ($query) use ($chat) {
            $query->where('chat_id', $chat->id);
            $query->where('user_id', auth()->user()->id);
        })->orderByDesc('created_at')->paginate(self::COUNT_MESSAGES);

        return response()->json((MessageCollection::make($messages)));
    }

    public function message(Chat $chat, Request $request): JsonResponse
    {
        if (ChatUsers::query()->where('chat_id', $chat->id)->where('user_id', auth()->user()->id)->get()) {
            $message = Message::query()->create([
                'user_id' => auth()->user()->id,
                'text' => $request->get('text'),
                'chat_id' => $chat->id,
            ]);
            SendMessage::dispatch($message);

            return response()->json([
                'success' => true,
                'message' => "Message created and job dispatched.",
            ]);
        }

        return response(status: Response::HTTP_FORBIDDEN)->json();
    }
}
