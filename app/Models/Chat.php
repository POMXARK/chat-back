<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 * @property mixed $user_id
 */
class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(ChatUsers::class);
    }
}
