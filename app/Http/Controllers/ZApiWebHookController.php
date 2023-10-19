<?php

namespace App\Http\Controllers;

use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\GroupQuestionsResponse;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\User;
use App\Models\VolunteerRegistration;
use App\Models\VolunteerRequest;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZApiWebHookController extends Controller
{
    public function getStatusMessage(Request $request)
    {
        
        Log::info("-------WebhoockLaravel---------");
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
        if (false) {
            $dados['phone'] = '5541989022440';
        }



        //verificar se este telefone tem um chamado em aberto
        $nextNedRequest = PrayerRequest::where('user_id', $user->id)->where('status_id', '!=', 3)->first();
        $selectTemplateQuestions =  DialogsTemplate::where('title', 'Egreja')->first();

        //pegar a primeira questão;



        //SE NÃO ESTIVER SIDO INICIADO UMA CONVERÇA FICA MANDANDO A PRIMEEIRA MENSAGEM DO TEMPLATE
        if (!$nextNedRequest) {
            $dialogQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->where('start', 1)->first();
            $this->createDefaultPrayerRequest($user, $dialogQuestion->id);
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $dialogQuestion->question));
            return;
        }

        //SE JA ESTIVER SIDO INICIADO UMA CONVERÇA VERIFICAR RESPOSTAS E TEMPLATES
        if ($nextNedRequest) {
            $message = $dados['text']['message'];
            $currentQueestionId = $nextNedRequest->current_dialog_question_id;

            //PEGA A MENSAGEM QUE O ASUARIO ENVIOU E SALVA;
            $this->saveMessage($nextNedRequest->id, $currentQueestionId, $dados['text']['message']);

            //verifica a resposta e traz o role_id dela caso for valida
            $resultVerifyQuestion = $this->verifyRoleResponse($message, $currentQueestionId);
        }
        //pega os parametros da proxima questão que devem ser executados;
        $objectNextComands = $this->nextQuestion($resultVerifyQuestion, $currentQueestionId);

        //verificar se tem algum methodo para ser executado;
        if (is_object($objectNextComands)) {
            if ($objectNextComands->next_question) {
                $nextQuestion = DialogsQuestion::find($objectNextComands->next_question);
                $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
                $this->updatePrayerRequest($objectNextComands->next_question, $nextNedRequest, 1);
            }

            if ($objectNextComands->method) {
                $this->executeMethod($objectNextComands->method);
            }
        } else {
            $nextQuestion = DialogsQuestion::find($currentQueestionId);
            switch ($objectNextComands) {
                case 'FORCE_STOP':
                    # code...
                    $nextQuestion = DialogsQuestion::find($nextQuestion->finish_dialog_question_id);
                    $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
                    break;

                default:
                    $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
                    $this->executeMethod($nextQuestion->method);
                    # code...
                    break;
            }
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

    public function createDefaultPrayerRequest($user, $dialogQuestionId)
    {
        $PrayerRequest = new PrayerRequest();
        $PrayerRequest->user_id = $user->id;
        $PrayerRequest->status_id = 1;
        $PrayerRequest->current_dialog_question_id = $dialogQuestionId;
        $PrayerRequest->save();
    }


    public function verifyRoleResponse($meessage, $idQuestion)
    {
        //verifica se existe um grupo de respostas para a questão;
        $existResponsesQuestion = $this->existResponsesQuestion($idQuestion);


        //se não existir um grupo de respostas
        // if (!$existResponsesQuestion) {
        //     return 'next';
        // }

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
        $nextQuestion = $currentQuestion->next_dialog_question_id;

        if (!$nextQuestion || $resultVerifyQuestion === 3) {
            return 'stop';
        }

        if (in_array($resultVerifyQuestion, [1, 'next'])) {
            return (object)["next_question" => $nextQuestion, "method" => $currentQuestion->method ?? null];
        } else if (in_array($resultVerifyQuestion, [2])) {
            return null;
        } else if (in_array($resultVerifyQuestion, [3])) {
            return "FORCE_STOP";
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


    public function executeMethod($metod)
    {
        switch ($metod) {
            case 'send_message_to_volunteers':
                # code...
                //peagar todos os voluntarios da tabela e enviar 
                $voluntariers = VolunteerRegistration::where('is_aproved', 1)->get();
                foreach ($voluntariers as $obj) {
                    # code...
                    $originalPhone = preg_replace("/[^0-9]/", "", $obj['phone']);
                    $zApiController = new ZApiController();
                    sleep(1);
                    $existPrayerRequest = VolunteerRequest::where('user_id', $obj['id'])->where('status_id', 1)->exists();

                    if ($originalPhone === "5541989022440") {
                        if (!$existPrayerRequest) {
                            $user = User::find($obj['id']);
                            $selectTemplateQuestions =  DialogsTemplate::where('title', 'Egreja-Voluntary')->first();
                            $dialogQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->where('start', 1)->first();
                            $this->createDefaultPrayerRequest($user, $dialogQuestion->id);
                            $zApiController->sendMessage($originalPhone, str_replace('\n', "\n", $dialogQuestion->question));
                        }
                    }
                }

                break;
            case 'acept_request_voluntary':
                dd('Hello');
                break;

            default:
                # code...
                break;
        }
    }
}
