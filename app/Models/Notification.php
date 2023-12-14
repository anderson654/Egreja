<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    /**
     * Pega apenas a que esta com status pendente.
     */
    public function pending_conversation()
    {
        return $this->hasOne(Conversation::class, 'id', 'conversation_id')->whereIn('status_conversation_id', [1, 2]);
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'id', 'conversation_id');
    }
}
