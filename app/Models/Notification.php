<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    use HasFactory;

    private $userPastor;

    public function __construct()
    {
        $this->userPastor = User::find(96);
    }


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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Abre uma notificação do tipo questionario
     * @param int $userId user que vai receber o questionario
     * @param int $conversationId id da conversa 
     * @return bool
     */
    public static function openQuestionaryUser($userId, $conversationId)
    {
        $notifications = new Notification;
        $notifications->user_id = $userId;
        $notifications->conversation_id = $conversationId;
        $notifications->status_notifications_id = 2;
        $notifications->type_notifications_id = 2;
        $notifications->save();

        return $notifications;
    }

    /**
     * esta função atualiza o status da notificação para aceito.
     * @param Notification $notification
     * @return void
     */
    public static function aceptedNotification($notification)
    {
        $notification->status_notifications_id = 1;
        $notification->update();
    }


    /**
     * Cria uma nova notificação.
     * @param  User $user
     * @param  Conversation $conversation
     * @param  int $type
     * @param  int $status Opicional: Valorpadrão é 2
     * @return void
     */
    public static function openNotification($user, $conversation, $type, $status = 2)
    {
        $notification = new Notification();
        $notification->user_id = $user->id;
        $notification->conversation_id = $conversation->id;
        $notification->status_notifications_id = $status;
        $notification->type_notifications_id = $type;
        $notification->save();
    }

    /**
     * Cria uma nova notificação para o pastor, referente a acompanhamento
     * @param Conversation $conversation
     * @return void
     */
    public static function openNotificationReception($conversation)
    {
        //selecionar o pastor responsavel
        $notification = new  Notification();
        $notification->openNotification($notification->userPastor, $conversation, 3);
    }
}
