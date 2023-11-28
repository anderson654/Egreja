<?php

namespace App\Models\WhatsApp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer_requests_id',
        'dialogs_questions_id',
        'response'
    ];

    /**
     * @param int $idPrayerRequest id da instancia PlayerRequests
     * @param int $idDialogQuestion id da instancia DialogsQuestions
     * @param string $message mensagen a ser salva pode vir do z-api
     * @return void
     */

    public static function saveMessage($idPrayerRequest, $idDialogQuestion, $message)
    {
        HistoricalConversation::create([
            'prayer_requests_id' => $idPrayerRequest,
            'dialogs_questions_id' => $idDialogQuestion,
            'response' => $message
        ]);
    }
}
