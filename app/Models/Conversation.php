<?php

namespace App\Models;

use App\Http\Controllers\ZApiController;
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
     * @param int $reference id da converça que fez abrir o chamado
     * @param int $statusId status que deve iniciar a converça.
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
     * Verifica se existe um voluntario na converça
     * @param int $conversationId id da converça
     * @return bool
     */
    public static function existUserInConversation($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        return isset($conversation->voluntary_id);
    }

    /**
     * Verifica se existe um voluntario na converça
     * @param Conversation $conversationId id da converça
     * @return void
     */
    public static function finishConversation($conversation)
    {
        $conversation->status_conversation_id = 3;
        $conversation->update();
    }

    /**
     * Seta o voluntario que aceitou o pedido na converça
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
     * Faz o boot abrir uma converça
     * @param int $userId
     * @param int $templateId id do template
     * @param int $referenceId  referencia caso queira colocar(id da converça que inicio esse evento)
     * @param int $userAcceptedId apenas para converça de template 1(inicio de converça user)
     * @return void
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
    }
}
