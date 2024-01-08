<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\GroupQuestionsResponse;
use App\Models\Message;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;

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
            HistoricalConversation::saveMessage($currentConversation, $date['text']['message']);

            $defaultFunctionsController = new DefaultFunctionsController($this->user, $date, $currentConversation);
            $defaultFunctionsController->nextDialogQuestion();
            return;
        }
        //Pendente
        if ($this->getConversationToStatus(2)) {
            // $this->openRequest($date);
            return;
        }

        $this->openRequest($date);






        // $this->date = $date;
        // //salva a mensagem no historico.
        // HistoricalConversation::saveMessage($this->conversations->id, $this->question->id, $this->date['text']['message']);

        // //inicias as funçoes padrão
        // $defaultFunctionsController = new DefaultFunctionsController($this->user, $date, $this->prayerRequests, $this->question);
        // $defaultFunctionsController->nextDialogQuestion();

        // //ajustar para as outras verificaçoes agora
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
        $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", $message->message));
        
        //criar uma reeferencia para prayer_requests para metrica.
        $this->newPrayerRequest($conversation->id);
    }


    /**
     * @param int $statusConversationId recebe um status para verificar se existe;
     * @return Conversation caso exista
     */
    public function getConversationToStatus($statusConversationId)
    {
        return Conversation::where('user_id', $this->user->id)->where('status_conversation_id', $statusConversationId)->first();
    }

    public function newPrayerRequest($conversationId){
        $prayerRequest = new PrayerRequest();
        $prayerRequest->user_id = $this->user->id;
        $prayerRequest->reference = $conversationId;
        $prayerRequest->status_id = 1;
        $prayerRequest->save();
    }
}
