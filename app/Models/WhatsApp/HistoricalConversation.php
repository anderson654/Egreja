<?php

namespace App\Models\WhatsApp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'messages_id',
        'response'
    ];

    /**
     * @param Conversation $conversation instancia Conversation
     * @param string $response mensagen a ser salva pode vir do z-api
     * @return void
     */

    public static function saveMessage($conversation, $response)
    {
        HistoricalConversation::create([
            'conversation_id' => $conversation->id,
            'messages_id' => $conversation->message->id,
            'response' => $response
        ]);
    }
}
