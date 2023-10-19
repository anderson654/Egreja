<?php

namespace App\Http\Controllers;

use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\GroupQuestionsResponse;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\User;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZApiWebHookController extends Controller
{
    public function getStatusMessage(Request $request)
    {
        $zApiController = new ZApiController();
        Log::info("-------WebhoockLaravel---------");
        $dados = $request->all();

        //se os dados vierem de um grupo não fazer nada;
        if ($dados['isGroup']) {
            return;
        }

        //se não existir criar um usuario
        $user = $this->createNewUser($dados);

        //deixe true para teste
        if (true) {
            $dados['phone'] = '5541989022440';
        }

        //verificar se este telefone tem um chamado em aberto
        $nextNedRequest = PrayerRequest::where('user_id', $user->id)->where('status_id', '!=', 3)->first();
        $selectTemplateQuestions =  DialogsTemplate::where('title', 'Egreja')->first();

        //SE NÃO ESTIVER SIDO INICIADO UMA CONVERÇA FICA MANDANDO A PRIMEEIRA MENSAGEM DO TEMPLATE
        if (!$nextNedRequest) {
            $dialogQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->where('priority', 1)->first();
            $this->createDefaultPrayerRequest($user);
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $dialogQuestion->question));
            return;
        }

        //SE JA ESTIVER SIDO INICIADO UMA CONVERÇA VERIFICAR RESPOSTAS E TEMPLATES
        if ($nextNedRequest) {
            $message = $dados['text']['message'];
            $currentQueestionId = $nextNedRequest->current_dialog_question_id;

            //PEEGA A MENSAGEM QUE O ASUARIO ENVIOU E SALVA;
            $this->saveMessage($nextNedRequest->id, $currentQueestionId, $dados['text']['message']);

            //verifica a resposta e traz o role_id dela caso for valida
            //next caso a pergunta não contenha grupo de respostas
            //null caso tenho grupo de respostas mas o sistema não consigiu identificar
            $resultVerifyQuestion = $this->verifyRoleResponse($message, $currentQueestionId);
        }
        //devolve o id da proxima questão caso o $resultVerifyQuestion seja valido;
        $nextQuestionId = $this->nextQuestion($resultVerifyQuestion, $currentQueestionId);

        $ultimateQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->orderBy('priority', 'desc')->first();
        if ($nextQuestionId === 'stop' || $nextQuestionId === $ultimateQuestion->id) {

            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $ultimateQuestion->question));
            $this->updatePrayerRequest($currentQueestionId, $nextNedRequest, 3);
            return;

        }

        if ($nextQuestionId) {
            $nextQuestion = DialogsQuestion::find($nextQuestionId);
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
            $this->updatePrayerRequest($nextQuestionId, $nextNedRequest, 1);
        } else {
            $currentQuestion = DialogsQuestion::find($currentQueestionId);
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", "não conseguimos identificar a resposta"));
            sleep(2);
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $currentQuestion->question));
        }

        return response()->json(['message' => 'Dados do webhook recebidos com sucesso'], 200);
    }

    public function saveMessage($idPrayerRequest, $idDialogQuestion, $message)
    {
        HistoricalConversation::create([
            'prayer_requests_id' => $idPrayerRequest,
            'dialogs_questions_id' => $idDialogQuestion,
            'response' => $message
        ]);
    }

    public function createDefaultPrayerRequest($user)
    {
        $PrayerRequest = new PrayerRequest();
        $PrayerRequest->user_id = $user->id;
        $PrayerRequest->status_id = 1;
        $PrayerRequest->current_dialog_question_id = 1;
        $PrayerRequest->save();
    }


    public function verifyRoleResponse($meessage, $idQuestion)
    {
        //verifica se existe um grupo de respostas para a questão;
        $existResponsesQuestion = $this->existResponsesQuestion($idQuestion);


        //se não existir um grupo de respostas
        if (!$existResponsesQuestion) {
            return 'next';
        }

        //se existir um grupo de respostas;
        $responseToGroup = $this->checkExistMessageInGroups($meessage, $idQuestion);
        //resposta não encontrada
        if (!$responseToGroup) {
            return null;
        }
        //resposta encontrada
        return $responseToGroup->group_response->responses_role_id ?? null;
    }

    public function existResponsesQuestion($idQuestion)
    {
        return GroupQuestionsResponse::where('dialog_question_id', $idQuestion)->whereHas('group_response', function ($query) {
            $query->where('responses_role_id', 1);
        })->exists();
    }

    public function checkExistMessageInGroups($meessage, $idQuestion)
    {
        //pega todos os grupos de resposta para esta questão
        $grupsResponses = GroupQuestionsResponse::where('dialog_question_id', $idQuestion)->has('group_response')->get();

        //fazer um pluck so id dos grupos;
        //este pluck esta errado
        $idsGroups = $grupsResponses->pluck('groups_responses_id');
        //verifica se em algun grupo existe uma resposta com esse valor;
        return ResponsesToGroup::where('response', $meessage)->whereIn('group_responses_id', $idsGroups)->with('group_response')->first();
    }

    public function createNewUser($dados)
    {
        $user = User::where('phone', $dados['phone'])->first();
        if (!$user) {
            $newUser = new User();
            $newUser->phone = $dados['phone'];
            $newUser->username = $dados['senderName'] ?? 'anonimo';
            $newUser->email = 'e_greja_' . rand(1, 1000000000) . '@gmail.com';
            $newUser->password = '123456789';
            $newUser->role_id = 4;
            if (!$newUser->save()) {
                Log::info('Erro ao salvar User: ' . $dados['phone']);
            }
            $user = $newUser;
        }
        return $user;
    }

    public function nextQuestion($resultVerifyQuestion, $currentQueestionId)
    {
        $currentQuestion = DialogsQuestion::find($currentQueestionId);
        $currentPriority = $currentQuestion->priority;
        $currentTemplate = $currentQuestion->dialog_template_id;

        $queryNextQuestion = DialogsQuestion::where('dialog_template_id', $currentTemplate)->where('priority', $currentPriority + 1);

        if (!$queryNextQuestion->exists() || $resultVerifyQuestion === 3) {
            return 'stop';
        }

        if (in_array($resultVerifyQuestion, [1, 'next'])) {
            $nextQuestion = $queryNextQuestion->first();
            return $nextQuestion->id;
        } else if (in_array($resultVerifyQuestion, [2])) {
            return null;
        } else {
            return null;
        }
    }

    public function updatePrayerRequest($questionId, $nextNedRequest, $statusId)
    {
        $nextNedRequest->current_dialog_question_id = $questionId;
        $nextNedRequest->status_id = $statusId;
        return $nextNedRequest->save();
    }
}
