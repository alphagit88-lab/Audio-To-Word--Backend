<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = [
        'guest_id', 'guest_name', 'guest_email', 'status', 'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
