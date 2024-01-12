<?php

namespace App\Models\WhatsApp;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HistoricalConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'messages_id',
        'response',
        'is_boot'
    ];

    protected $append = [
        'simple_date'
    ];

    /**
     * @param Conversation $conversation instancia Conversation
     * @param string $response mensagen a ser salva pode vir do z-api
     * @return void
     */

    public static function saveMessage($conversation, $response, $isBoot)
    {
        HistoricalConversation::create([
            'conversation_id' => $conversation->id,
            'messages_id' => null,
            'response' => $response,
            'is_boot' => $isBoot
        ]);
    }
    public function message()
    {
        return $this->hasOne(Message::class, 'id', 'messages_id');
    }

    public function getSimpleDateAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->subHours(3)->format('d/m/Y H:i');
        return $dataFormat;
    }

    public function getCreatedAtAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->subHours(3)->format('d/m/Y H:i:s');
        return $dataFormat;
    }
}
