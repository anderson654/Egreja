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
}
