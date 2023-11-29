<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\DialogsQuestion;
use App\Models\GroupQuestionsResponse;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\SideDishes;
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
                $this->executeMethods($this->question->negaative_response_method);
                break;
            case 3:
                # code...
                break;
            case 4:
                # code...
                //não  possui um grupo de respostas
                $this->executeMethods($this->question->not_exist_group_responses_method);
                break;
            case 5:
                # code...
                $this->executeMethods($this->question->not_indentify_response_method ?? 'not_identify_response');
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
                $this->nextQuestion();
                break;
            case 'not_identify_response':
                # code...
                $this->notIdentifyResponse();
                break;
            case 'send_message_to_volunteers':
                # code...
                $this->sendMessageToVolunteers();
                break;
            case 'acept_request_voluntary':
                # code...
                $this->aceptRequestVoluntary();
                break;
            case 'negative_response_template_one':
                # code...
                $this->negativeResponseTemplateOne();
                break;
            case 'dificult':
                # code...
                $this->dificult();
                break;
            case 'save_response_dificult':
                # code...
                $this->saveResponseDificult();
                break;
            case 'problem_prayer':
                # code...
                $this->problemPrayer();
                break;
            case 'describ_problem_prayer':
                # code...
                $this->describProblemPrayer();
                break;
            case 'problem_prayer_response':
                # code...
                $this->problemPrayerResponse();
                break;
            case 'call_menber':
                # code...
                $this->callMenber();
                break;
            case 'finish_one':
                # code...
                $this->finishOne();
                break;
            case 'finish_two':
                # code...
                $this->finishTwo();
                break;
            case 'positive_one':
                # code...
                $this->positiveOne();
                break;
            case 'finish_tree':
                # code...
                $this->finishTree();
                break;

            default:
                # code...
                break;
        }
    }

    //metodos default;
    public function nextQuestion()
    {
        $this->manualyNextQuestion($this->question->priority + 1);
    }

    public function notIdentifyResponse()
    {
        $this->zApiController->sendMessage($this->user->phone, str_replace('\n', "\n", "Não foi possível identificar a resposta."));
    }

    public function closePrayerRequest()
    {
        $this->prayerRequests->status_id = 3;
        $this->prayerRequests->save();
    }

    public function updatePrayerRequest($id)
    {
        $this->prayerRequests->current_dialog_question_id = $id;
        $this->prayerRequests->save();
    }

    public function manualyNextQuestion($priority){
        $nextQuestion = DialogsQuestion::where('dialog_template_id', $this->question->dialog_template_id)->where('priority', $priority)->first();
        $this->zApiController->sendMessage($this->user->phone, str_replace('\n', "\n", $nextQuestion->question));
        $this->updatePrayerRequest($nextQuestion->id);
    }
    //fim metodos default;



    /**
     * @param PrayerRequest $prayerRequest
     */
    public function finishPrayerRequest($prayerRequest)
    {
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


    public function negativeResponseTemplateOne()
    {
        $this->manualyNextQuestion(3);
        $this->closePrayerRequest();
    }



    //negative responses
    // public function didNotRespond(){
    //     $nextQuestion = DialogsQuestion::where('dialog_template_id',$this->question->dialog_template_id)->where('priority', 10)->first();
    //     $this->zApiController->sendMessage($this->user->phone, str_replace('\n', "\n", $nextQuestion->question));
    // }

    public function dificult()
    {
        $this->manualyNextQuestion(5);
    }

    public function saveResponseDificult()
    {
        $this->nextQuestion();
        $this->closePrayerRequest();
    }
    public function problemPrayer()
    {
        if ($this->date['text']['message']  ==  7) {
            $this->manualyNextQuestion(4);
            return;
        }
        $this->nextQuestion();
        $this->closePrayerRequest();
    }

    public function describProblemPrayer()
    {
        $this->manualyNextQuestion(3);
        $this->closePrayerRequest();
    }

    public function  problemPrayerResponse(){
        $this->manualyNextQuestion(10);
        $this->closePrayerRequest();
    }
    public function callMenber(){
        $this->createNewSideDishers();
        $this->nextQuestion();
        $this->closePrayerRequest();
    }
    public function finishOne(){
        $this->manualyNextQuestion(11);
        $this->closePrayerRequest();
    }
    public function finishTwo(){
        $this->manualyNextQuestion(7);
    }
    public function positiveOne(){
        $this->manualyNextQuestion(5);
    }
    public function finishTree(){
        $this->manualyNextQuestion(9);
        $this->closePrayerRequest();
    }


    public function createNewSideDishers(){
        //logica de acolhimento
        $sideDishes = new SideDishes();
        $sideDishes->user_id = $this->prayerRequests->user_id;
        //id do responsavel
        $sideDishes->responsible_user_id = 45;
        $sideDishes->save();
    }
}
