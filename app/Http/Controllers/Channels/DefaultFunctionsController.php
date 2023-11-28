<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\DialogsQuestion;
use App\Models\GroupQuestionsResponse;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\User;
use Illuminate\Http\Request;

class DefaultFunctionsController extends Controller
{
    private $user;
    private $date;
    private $zApiController;
    private $prayerRequests;
    private $question;


    /**
     * @param User $user Recebe o usuario
     * @param object $date objeto z-api
     * @param PrayerRequest $prayerRequest
     * @param DialogsQuestion $question type dialogs_questions
     */
    public function __construct($user, $date, $prayerRequest, $question)
    {
        $this->zApiController = new ZApiController();
        $this->date =  $date;
        $this->user = $user;
        $this->prayerRequests = $prayerRequest;
        if ($this->prayerRequests && isset($this->prayerRequests->current_dialog_question_id)) {
            $this->question =  $question;
        }
    }

    //daqui para baixo verificar o que fazer;
    public function nextDialogQuestion()
    {
        //verificar se tem resposta;
        //uma resposta positiva negativa ou um dialogo de encerramento?

        $existResponse = GroupQuestionsResponse::existResponsesQuestion($this->question->id);
        if (!$existResponse) {
            //caso o grupo de respostas não exista
            $this->filterAndExecuteMethods(4);
            return;
        }

        $responseToGroup = ResponsesToGroup::verifyRoleResponse($this->date['text']['message'], $this->question->id);
        //pega o role da resposta
        // $this->filterAndExecuteMethods($responseToGroup->group_response);
        if (!$responseToGroup) {
            //executar methodo caso exista grupo mais resposta não identificada
            $this->filterAndExecuteMethods(5);
            return;
        }

        $this->filterAndExecuteMethods($responseToGroup->group_response->responses_role_id);
    }

    public function filterAndExecuteMethods($roleResponse)
    {
        switch ($roleResponse) {
            case 1:
                # code...
                $this->executeMethods($this->question->positive_response_method ?? 'next_question');
                break;
            case 2:
                # code...
                break;
            case 3:
                # code...
                break;
            case 4:
                # code...
                break;
            case 5:
                # code...
                dd('Hello');
                break;
            default:
                # code...
                break;
        }
    }

    public function executeMethods($name)
    {
        switch ($name) {
            case 'next_question':
                # code...
                break;
            case 'send_message_to_volunteers':
                # code...
                $this->sendMessageToVolunteers();
                break;
            case 'acept_request_voluntary':
                # code...
                $this->aceptRequestVoluntary();
                break;

            default:
                # code...
                break;
        }
    }


    public function nextQuestion()
    {
        $nextQuestion = DialogsQuestion::where('priority', $this->question->priority + 1)->first();
        $this->prayerRequests->current_dialog_question_id = $nextQuestion->id;
        $this->prayerRequests->save();
    }

    /**
     * @param PrayerRequest $prayerRequest
     */
    public function finishPrayerRequest($prayerRequest){
        $prayerRequest->status_id = 3;
        $prayerRequest->save();
    }

    public function sendMessageToVolunteers()
    {
        $voluntaryController =  new VoluntaryController($this->user);
        $this->prayerRequests->status_id = 4;
        $dialogQuestion = DialogsQuestion::where('dialog_template_id', 2)->where('priority', 1)->first();
        $voluntaryController->sendMessageAllVoluntaries($dialogQuestion->question, $dialogQuestion);
        $this->nextQuestion();
    }

    public function aceptRequestVoluntary()
    {
        //verifica se tem um usuario na chamada
        $payerRequeest = PrayerRequest::find($this->prayerRequests->reference);

        if (isset($payerRequeest->voluntary_id)) {
            $this->zApiController->sendMessage($this->user->phone, str_replace('\n', "\n", "Este atendimento ja foi aceito por outro voluntário. \nObrigado."));
            $this->finishPrayerRequest($this->prayerRequests);
            return;
        }
        
        //pegar o user
        $prayer = User::find($payerRequeest->user_id);
        $this->zApiController->sendMessage($this->user->phone, str_replace('\n', "\n", "Voce aceitou atender ao atendimento.\nLigue para $prayer->username\nTelefone: $prayer->phone"));
        
        $payerRequeest->voluntary_id = $this->user->id;
        $payerRequeest->status_id = 2;
        $payerRequeest->save();
        $this->finishPrayerRequest($this->prayerRequests);
    }

}
