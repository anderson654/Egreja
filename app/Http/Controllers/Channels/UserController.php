<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\DialogsTemplate;
use App\Models\Message;
use App\Models\PrayerRequest;
use App\Models\WhatsApp\HistoricalConversation;

class UserController extends Controller
{
    private $user;
    private $zApiController;

    /**
     * @param User $user Recebe o usuario
     */
    public function __construct($user)
    {
        $this->zApiController = new ZApiController();
        $this->user = $user;
    }

    //controla as chamadas do voluntario.
    /**
     * @param object $date Dados do z-api
     * @return void
     */
    public function initChanelUser($date)
    {
        //Ativa
        if ($this->getConversationToStatus(1)) {
            $currentConversation = $this->getConversationToStatus(1);
            HistoricalConversation::saveMessage($currentConversation, $date['text']['message'],false);

            $defaultFunctionsController = new DefaultFunctionsController($this->user, $date, $currentConversation);
            $defaultFunctionsController->nextDialogQuestion();
            return;
        }
        //Pendente
        if ($this->getConversationToStatus(2)) {
            return;
        }
        $this->openRequest($date);
    }

    /**
     * @param object $date Dados do z-api
     * @return void
     */
    public function openRequest($date)
    {
        $selectTemplateQuestions = DialogsTemplate::where('title', 'Egreja')->first();
        $message = Message::where('template_id', $selectTemplateQuestions->id)->where('priority', 1)->first();
        $conversation = Conversation::newConversation($this->user, $message);
        //salva a mensagem do user
        HistoricalConversation::saveMessage($conversation, $date['text']['message'], false);
        //salva a mensagem do boot na converça
        $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", $message->message));
        HistoricalConversation::saveMessage($conversation, $message->message, true);
    }


    /**
     * @param int $statusConversationId recebe um status para verificar se existe;
     * @return Conversation caso exista
     */
    public function getConversationToStatus($statusConversationId)
    {
        return Conversation::where('user_id', $this->user->id)->where('status_conversation_id', $statusConversationId)->first();
    }

    /**
     * @return PrayerRequest
     */

    public function newPrayerRequest($conversationId)
    {
        $prayerRequest = new PrayerRequest();
        $prayerRequest->user_id = $this->user->id;
        $prayerRequest->reference = $conversationId;
        $prayerRequest->status_id = 5;
        $prayerRequest->number_of_notifications = 1;
        if (!$prayerRequest->save()) {
            return null;
        }
        return $prayerRequest;
    }
}
