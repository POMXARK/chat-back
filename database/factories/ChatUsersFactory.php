<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ChatUsers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ChatUsers>
 */
final class ChatUsersFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ChatUsers::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'chat_id' => \App\Models\Chat::factory(),
        ];
    }
}
