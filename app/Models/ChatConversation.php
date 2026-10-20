<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property \Carbon\Carbon|null $last_message_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatMessage> $messages
 * @property-read \App\Models\ChatMessage|null $latestMessage
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatConversation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatConversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatConversation where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatConversation with($relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatConversation orderBy($column, $direction = 'asc')
 * @method static int count($columns = '*')
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class ChatConversation extends Model
{
    use HasFactory;

    protected $table = 'chat_conversations';

    protected $fillable = [
        'user_id',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latestOfMany();
    }

    public function unreadCountForAdmin(): int
    {
        return $this->messages()
            ->where('is_read', false)
            ->where('sender_id', $this->user_id)
            ->count();
    }

    public function unreadCountForCustomer(): int
    {
        return $this->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', $this->user_id)
            ->count();
    }
}

