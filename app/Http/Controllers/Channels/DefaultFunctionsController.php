<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\DialogsQuestion;
use App\Models\GroupQuestionsResponse;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\SideDishes;
use App\Models\User;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DefaultFunctionsController extends Controller
{
    private $user;
    private $date;
    private $zApiController;
    private $conversation;
    private $question;
    private $methods;
    private $paramns;
    private $userPastor;


    /**
     * @param User $user Recebe o usuario
     * @param object $date objeto z-api
     * @param Conversation $conversation
     * @param Message $question type dialogs_questions
     */
    public function __construct($user, $date, $conversation)
    {
        $this->zApiController = new ZApiController();
        $this->date =  $date;
        $this->user = $user;
        $this->conversation = $conversation;
        if ($this->conversation && isset($this->conversation->messages_id)) {
            $this->question = $this->conversation->message;
        }
        //mapeia os métodos
        $this->mapedFunctions();
        $this->userPastor = User::find(65);
    }

    /**
     * Mapeia as funçoes 
     * @return void
     */
    public function mapedFunctions()
    {
        $this->methods = [
            'next_question' => 'nextQuestion',
            'not_identify_response' => 'notIdentifyResponse',
            'send_message_to_volunteers' => 'sendMessageToVolunteers',
            'acept_request_voluntary' => 'aceptRequestVoluntary',
            'negative_response_template_one' => 'negativeResponseTemplateOne',
            'dificult' => 'dificult',
            'save_response_dificult' => 'saveResponseDificult',
            'problem_prayer' => 'problemPrayer',
            'describ_problem_prayer' => 'describProblemPrayer',
            'problem_prayer_response' => 'problemPrayerResponse',
            'call_menber' => 'callMenber',
            'finish_one' => 'finishOne',
            'finish_two' => 'finishTwo',
            'positive_one' => 'positiveOne',
            'finish_tree' => 'finishTree',
            'force_accept_voluntary' => 'forceAceptVoluntary',
            'next_and_close' => ['nextQuestion', 'closePrayerRequest'],
            'recuse_prayer' => 'recusePrayer',
            'wait' => 'wait',
            'wait_close_request' => 'waitCloseRequest'
        ];
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

        if (isset($this->methods[$name])) {
            $method = $this->methods[$name];
            if (is_array($method)) {
                foreach ($method as $m) {
                    $this->$m();
                }
            } else {
                $this->$method();
            }
        } else {
            // Lidar com o caso padrão ou desconhecido, se necessário
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
        $this->conversation->status_conversation_id = 3;
        $this->conversation->save();
    }

    public function updatePrayerRequest($id)
    {
        $this->conversation->messages_id = $id;
        $this->conversation->save();
    }

    public function failPrayerRequest()
    {
        $conversation = Conversation::find($this->conversation->reference_conversation_id);
        $conversation->status_conversation_id = 2;
        $conversation->save();
    }

    public function manualyNextQuestion($priority)
    {
        $nextMessage = $this->sendNextMessage($priority);
        $this->updatePrayerRequest($nextMessage->id);
    }

    public function sendNextMessage($priority)
    {
        $nextMessage = Message::where('template_id', $this->question->template_id)->where('priority', $priority)->first();
        $message = $nextMessage->message;
        if ($this->paramns) {
            $message = Utils::setDefaultNames($this->paramns, $message);
        }
        $this->zApiController->sendMessage($this->user->phone, str_replace('\n', "\n", $message));
        return $nextMessage;
    }

    public function createNewSideDishers()
    {
        Notification::openNotificationReception($this->conversation);
        SideDishes::createNewSideDishes($this->conversation->user->id, $this->userPastor->id, $this->conversation->id);
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

    /**
     * Esta função envia uma mensagem para todos os voluntarios que não estiverem em outra chamada.
     */
    public function sendMessageToVolunteers()
    {
        $this->nextQuestion();

        $voluntaryController =  new VoluntaryController($this->user);
        $nextMessage = Message::where('template_id', 2)->where('priority', 1)->first();

        //abrir uma notificação na tabela
        $userController = new UserController($this->user);
        $payerRequest = $userController->newPrayerRequest($this->conversation->id);

        $this->paramns = [
            "id" => $payerRequest->id,
            "datetime" => $payerRequest->created_at
        ];

        //seta atributos na mensagem
        if ($this->paramns) {
            $nextMessage->message = Utils::setDefaultNames($this->paramns, $nextMessage->message);
        }

        $voluntaryController->sendMessageAllVoluntaries($nextMessage, $this->conversation, $this->user->tester);
    }

    public function aceptRequestVoluntary()
    {
        $phone = $this->user->getRawOriginal('phone');
        //verifica se já possui um voluntario na chamada
        $existVoluntary = Conversation::existUserInConversation($this->conversation->reference_conversation_id);

        // dd($this->user->getRawOriginal('phone'));
        if ($existVoluntary) {
            $this->zApiController->sendMessage($phone, str_replace('\n', "\n", "Este atendimento ja foi aceito por outro voluntário. \nObrigado."));
            Conversation::finishConversation($this->conversation);
            return;
        }

        //esta é a referencia
        $conversationPrayer = Conversation::find($this->conversation->reference_conversation_id);
        //quem solicitou o atendimento.
        $prayer = User::find($conversationPrayer->user_id);
        //seta quem aceitou na chamada; //aqui tem que ser ajustado verificar e setar.
        Conversation::setUserAcept($conversationPrayer, $this->conversation->user_id);

        //fecha todas as abertas quando alguem aceita menos a de quem aceitou;
        Conversation::where('reference_conversation_id',  $conversationPrayer->id)->where('user_id', '!=', $this->conversation->user_id)->update(['status_conversation_id' => 3]);

        $this->paramns = [
            "username" => $prayer->username,
            "phone" => $prayer->phone
        ];

        $this->nextQuestion();


        //abre um questionario para o user
        Notification::openQuestionaryUser($prayer->id, $conversationPrayer->id);
        //abre um questionario para o voluntario
        Notification::openQuestionaryUser($this->conversation->user_id, $conversationPrayer->id);
        //atualiza o status do prayer_reequest
        $this->conversation->prayer_request_reference->status_id = 3;
        $this->conversation->prayer_request_reference->save();
    }

    //ajustar para criar uma nova notificação de pedido de ajuda.
    public function forceAceptVoluntary()
    {
        if (in_array($this->date['text']['message'], ['Atender demanda', 1])) {
            //pegar o user
            $userId = 7;
            $user = User::find($userId);

            $payerRequeest = PrayerRequest::find($this->conversation->reference);

            $username =  $payerRequeest->user->username;
            $phone =  $payerRequeest->user->phone;

            $this->zApiController->sendMessage($user->phone, str_replace('\n', "\n", "Voce aceitou atender ao pedido de oração.\nLigue para $username\nTelefone: $phone"));
            //close current PrayerRequest
            $this->conversation->status_id = 3;

            $payerRequeest->voluntary_id = $userId;
            $payerRequeest->status_id = 3;
            $payerRequeest->created_at = Carbon::now();
            $payerRequeest->update();

            $this->finishPrayerRequest($this->conversation);
        } else if (in_array($this->date['text']['message'], ['Redirecionar', 2])) {
            $this->forceAceptAllVoluntaries();
        }
    }


    public function forceAceptAllVoluntaries()
    {
        $payerRequeest = PrayerRequest::find($this->conversation->reference);
        $payerRequeest->questionary_user = 1;
        $payerRequeest->save();

        $newPrayerRequest = $payerRequeest->replicate();

        //antigo
        $this->finishPrayerRequest($payerRequeest);

        //novo
        $newPrayerRequest->voluntary_id = null;
        $newPrayerRequest->questionary_user = null;
        $newPrayerRequest->questionary_brother = null;
        $newPrayerRequest->status_id = 1;
        $newPrayerRequest->save();

        $voluntaryController =  new VoluntaryController($payerRequeest->user);
        $payerRequeest->status_id = 4;
        $dialogQuestion = DialogsQuestion::where('dialog_template_id', 2)->where('priority', 1)->first();
        $voluntaryController->sendMessageAllVoluntaries($dialogQuestion->question, $dialogQuestion, $this->user->tester);
        $this->manualyNextQuestion(3);

        $this->finishPrayerRequest($this->conversation);
    }




    public function negativeResponseTemplateOne()
    {
        $this->manualyNextQuestion(3);
        $this->closePrayerRequest();
    }

    public function dificult()
    {
        $this->manualyNextQuestion(4);
        $this->failPrayerRequest();
    }

    public function saveResponseDificult()
    {
        $this->nextQuestion();
        $this->closePrayerRequest();
    }
    public function problemPrayer()
    {
        if (in_array($this->date['text']['message'], [7, 'outros', 'Outros'])) {
            $this->manualyNextQuestion(7);
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

    public function problemPrayerResponse()
    {
        $this->manualyNextQuestion(10);
        $this->closePrayerRequest();
    }
    public function callMenber()
    {
        $this->createNewSideDishers();
        $this->nextQuestion();
        $this->closePrayerRequest();
    }
    public function finishOne()
    {
        $this->manualyNextQuestion(11);
        $this->closePrayerRequest();
    }
    public function finishTwo()
    {
        $this->manualyNextQuestion(7);
    }
    public function positiveOne()
    {
        $this->manualyNextQuestion(5);
    }
    public function finishTree()
    {
        $this->manualyNextQuestion(3);
        $this->closePrayerRequest();
    }

    public function recusePrayer()
    {
        $this->manualyNextQuestion(3);
        $this->closePrayerRequest();
    }

    public function wait()
    {
        Log::info($this->date['text']['message']);
        if ($this->date['text']['message'] !== '') {
            $this->sendNextMessage(4);
        }
    }
    public function waitCloseRequest()
    {
        //esta é a referencia
        $conversationPrayer = Conversation::find($this->conversation->reference_conversation_id);
        //quem solicitou o atendimento.
        $prayer = User::find($conversationPrayer->user_id);

        $this->paramns = [
            "username" => $prayer->username,
            "phone" => $prayer->phone
        ];
        $this->sendNextMessage(4);
    }
}
