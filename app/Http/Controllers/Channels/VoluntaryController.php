<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\DialogsQuestion;
use App\Models\Notification;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;

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
    public function __construct($user = null)
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
        //se existe uma converça aberta continua se não avisa que não tem chhamados em aberto.
        // dd($this->conversation);
        // $isAttending = $this->checkIsAttending();

        // dd($isAttending);


        // if ($isAttending) {
        //     $prayer = User::find($isAttending->user_id);
        //     $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", "Você já aceitou atender a um pedido de oração.\nLigue para $prayer->username\nTelefone: $prayer->phone"));
        //     return;
        // }

        //se não existir nenhuma chamada em aberto retornar
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
     * @param Conversation $conversation é a converça da qual partiu o chamado
     */
    public function sendMessageAllVoluntaries($message, $conversation = null)
    {
        // dd($dialogQuestion);
        //pegar todos menos aqueles que possuem dialogos em aberto;
        //existe 1,2,4 em aberto?
        $voluntaries = User::getVoluntariesNotAttending();
        // dd($voluntaries);
        if ($voluntaries->count() > 0) {
            foreach ($voluntaries as $voluntary) {
                # code...
                $phone = $voluntary->getRawOriginal('phone');
                if ($phone === "554195640242") {
                    if ($conversation) {
                        Conversation::newConversation($voluntary, $message, $conversation->id, 1);
                    }
                    $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message->message));
                }
            }
        } else {
            //verifica se essa notificação já existe.
            $existTypeNotification = Notification::where('conversation_id',$conversation->id)
            ->where('user_id',$conversation->user_id)
            ->where('status_notifications_id',2)
            ->where('type_notifications_id',1)
            ->exists();

            if($existTypeNotification){
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
     * Verifica  se o voluntario esta em um atendimento ou esta em uma converça;
     * @return  Conversation
     */
    public function checkIsAttending()
    {
        $conversation = Conversation::where('status_conversation_id', 1)->where('user_accepted', $this->user->id)->orWhere('user_id', $this->user->id)->first();
        return $conversation;
    }
}
