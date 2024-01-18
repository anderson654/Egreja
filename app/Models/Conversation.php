<?php

namespace App\Models;

use App\Http\Controllers\ZApiController;
use App\Models\WhatsApp\HistoricalConversation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_conversation_id'
    ];


    /**
     * @param User
     * @param Message $message mensagen.
     * @param int $reference id da conversa que fez abrir o chamado
     * @param int $statusId status que deve iniciar a conversa.
     * - 1 ativa.
     * - 2 pendente.
     * - 3 encerrada.
     */
    public static function newConversation($user, $message, $reference = null, $statusId = null)
    {
        $prayerRequest = new Conversation();
        $prayerRequest->user_id = $user->id;
        $prayerRequest->messages_id = $message->id;
        $prayerRequest->status_conversation_id = $statusId ?? 1;
        $prayerRequest->reference_conversation_id = $reference;
        $prayerRequest->save();

        return $prayerRequest;
    }

    public function message()
    {
        return $this->hasOne(Message::class, 'id', 'messages_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Verifica se existe um voluntario na conversa
     * @param int $conversationId id da conversa
     * @return bool
     */
    public static function existUserInConversation($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        return isset($conversation->voluntary_id);
    }

    /**
     * Verifica se existe um voluntario na conversa
     * @param Conversation $conversationId id da conversa
     * @return void
     */
    public static function finishConversation($conversation)
    {
        $conversation->status_conversation_id = 3;
        $conversation->update();
    }

    /**
     * Seta o voluntario que aceitou o pedido na conversa
     * @param Conversation $conversation
     * @param int $userId
     * @return void
     */
    public static function setUserAcept($conversation, $userId)
    {
        $conversation->user_accepted = $userId;
        $conversation->update();
    }

    /**
     * Faz o boot abrir uma conversa
     * @param int $userId
     * @param int $templateId id do template
     * @param int $referenceId  referencia caso queira colocar(id da conversa que inicio esse evento)
     * @param int $userAcceptedId apenas para conversa de template 1(inicio de conversa user)
     * @return Conversation
     */
    public static function openConversation($userId, $templateId, $referenceId = null, $userAcceptedId = null)
    {
        $message = Message::where('template_id', $templateId)->where('priority', 1)->first();
        $conversation = new Conversation();
        $conversation->user_id = $userId;
        $conversation->messages_id = $message->id;
        $conversation->status_conversation_id = 1;
        $conversation->reference_conversation_id = $referenceId;
        $conversation->user_accepted = $userAcceptedId;
        $conversation->save();

        return $conversation;
    }

    public function prayer_request()
    {
        return $this->hasOne(PrayerRequest::class, 'reference', 'id');
    }

    public function prayer_request_reference()
    {
        return $this->hasOne(PrayerRequest::class, 'reference', 'reference_conversation_id');
    }

    public function voluntary()
    {
        return $this->hasOne(User::class, 'id', 'user_accepted');
    }

    public function historical_conversation()
    {
        return $this->hasMany(HistoricalConversation::class, 'conversation_id', 'id');
    }

    public function getCreatedAtAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->subHours(3)->format('d/m/Y H:i:s');
        return $dataFormat;
    }

    /**
     * Verifica se o user passado esta em uma converça em aberto.
     * @param User $user recebe um usuario.
     * @return bool
     */
    public static function verifyUserInConversation(User $user)
    {
        return Conversation::where('status_conversation_id', 1)->where('user_id', $user->id)->exists();
    }

    public function reference_conversation()
    {
        return $this->hasOne(Conversation::class, 'id', 'reference_conversation_id');
    }
}
