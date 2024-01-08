<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\DialogsQuestion;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoluntaryController extends Controller
{
    private $zApiController;
    private $conversation;
    private $user;
    private $date;
    private $question;

    /**
     * @param User $user Recebe o usuario
     */
    public function __construct($user)
    {
        $this->zApiController = new ZApiController();
        $this->user = $user;
        $this->conversation = Conversation::where('user_id', $user->id)->where('status_conversation_id', 1)->first();
        if ($this->conversation) {
            $this->question =  $this->conversation->message;
        }
    }



    //controla as chamadas do voluntario.
    /**
     * @param object $date Dados do z-api
     * @return void
     */
    public function initChanelVoluntary($date)
    {
        if (!$this->conversation) {
            $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", "Não há chamados a serem atendidos."));
            return;
        }
        $this->date = $date;
        //salva a mensagem no historico.
        HistoricalConversation::saveMessage($this->conversation, $this->date['text']['message']);

        //inicias as funçoes padrão
        $defaultFunctionsController = new DefaultFunctionsController($this->user, $this->date, $this->conversation);
        $defaultFunctionsController->nextDialogQuestion();
    }


    /**
     * @param Message $message a mensagem a ser enviada
     * @param Conversation $conversation é a conversa da qual partiu o chamado
     */
    public function sendMessageAllVoluntaries($message, $conversation = null)
    {
        $voluntaries = User::getVoluntariesNotAttending();
        // dd($voluntaries->count());

        if ($voluntaries->count() > 10) {
            foreach ($voluntaries as $voluntary) {
                //verificar se alguem já aceitou e para de enviar



                # code...
                $phone = $voluntary->getRawOriginal('phone');
                // if ($phone === "554189022440") {
                    if ($conversation) {
                        Conversation::newConversation($voluntary, $message, $conversation->id, 1);
                    }
                    $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message->message));
                // }
            }

            //soma +1 no envio de notificação do prayer request
            if (isset(($conversation->prayer_request))) {
                $conversation->prayer_request->number_of_notifications .= 1;
                $conversation->prayer_request->save();
            }
        } else {
            //verifica se essa notificação já existe.
            $existTypeNotification = Notification::where('conversation_id', $conversation->id)
                ->where('user_id', $conversation->user_id)
                ->where('status_notifications_id', 2)
                ->where('type_notifications_id', 1)
                ->exists();

            if ($existTypeNotification) {
                return;
            }

            $notification = new Notification();
            $notification->user_id = $conversation->user_id;
            $notification->conversation_id = $conversation->id;
            $notification->status_notifications_id = 2;
            $notification->type_notifications_id = 1;
            $notification->save();
        }
    }

    /**
     * Verifica  se o voluntario esta em um atendimento ou esta em uma conversa;
     * @return  Conversation
     */
    public function checkIsAttending()
    {
        $conversation = Conversation::where('status_conversation_id', 1)->where('user_accepted', $this->user->id)->orWhere('user_id', $this->user->id)->first();
        return $conversation;
    }

    /**
     * @param int $referenceId referencia da conversation
     */
    public function resendMessageAllVoluntaries($referenceId)
    {
        $conversations = Conversation::where('reference_conversation_id', $referenceId)
            ->where('status_id', 1)
            ->where('messages_id', 4)
            ->with('user')
            ->has('user')
            ->get();

        if (!isset($conversations[0]->prayer_request)) {
            return;
        }

        if ($conversations[0]->prayer_request->number_of_notifications == 3) {
            return;
        }

        $message = Message::find(4);
        foreach ($conversations as $key => $conversation) {
            # code...
            $this->zApiController->sendMessage($conversation->user->phone, str_replace('\n', "\n", $message->message));
        }

        $conversations[0]->prayer_request->number_of_notifications .= 1;
        $conversation->prayer_request->save();

        Log::channel('notify_prayer_request')->info('Passou aqui');
    }
}
