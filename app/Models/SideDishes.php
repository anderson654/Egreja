<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideDishes extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Essa função cria um novo acompanhamento.
     * @param int $userId 
     * @param int $responsableId 
     * @param int $conversationId 
     */
    public static function createNewSideDishes($userId, $responsableId, $conversationId)
    {
        $sideDishes = new SideDishes();
        $sideDishes->user_id = $userId;
        $sideDishes->responsible_user_id = $responsableId;
        $sideDishes->conversation_id = $conversationId;
        $sideDishes->save();
    }

    public function getCreatedAtAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->format('d/m/Y');
        return $dataFormat;
    }
}
