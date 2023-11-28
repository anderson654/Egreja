<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\GroupQuestionsResponse;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;
    private $date;
    private $zApiController;
    private $prayerRequests;
    private $question;


    /**
     * @param User $user Recebe o usuario
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->zApiController = new ZApiController();
        $this->prayerRequests = PrayerRequest::where('user_id', $user->id)->whereIn('status_id', [1, 2, 4])->first();
        if ($this->prayerRequests && isset($this->prayerRequests->current_dialog_question_id)) {
            $this->question =  DialogsQuestion::find($this->prayerRequests->current_dialog_question_id);
        }
    }



    //controla as chamadas do voluntario.
    /**
     * @param object $date Dados do z-api
     * @return void
     */
    public function initChanelUser($date)
    {
        //se não existir nenhuma chamada em aberto retornar
        if (!$this->prayerRequests) {
            $this->openRequest($date);
            return;
        }
        $this->date = $date;
        //salva a mensagem no historico.
        HistoricalConversation::saveMessage($this->prayerRequests->id, $this->question->id, $this->date['text']['message']);

        //inicias as funçoes padrão
        $defaultFunctionsController = new DefaultFunctionsController($this->user, $date, $this->prayerRequests, $this->question);
        $defaultFunctionsController->nextDialogQuestion();

        //ajustar para as outras verificaçoes agora
    }


    /**
     * @param object $date Dados do z-api
     * @return void
     */
    public function openRequest($date)
    {
        $selectTemplateQuestions = DialogsTemplate::where('title', 'Egreja')->first();
        $dialogQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->where('priority', 1)->first();
        PrayerRequest::newPrayerRequest($this->user, $dialogQuestion);
        $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", $dialogQuestion->question));
    }
}
