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
        $dados['phone'] = substr($dados['phone'], 0, 4) . "9" . substr($dados['phone'], 4);

        //se não existir criar um usuario
        $user = $this->createNewUser($dados);

        //deixe true para teste
        if (false) {
            $dados['phone'] = '5541989022440';
        }

        //verificar se este telefone tem um chamado em aberto
        $nextNedRequest = PrayerRequest::where('user_id', $user->id)->where('status_id', '!=', 3)->first();

        if ($user->role_id === 3 && !$nextNedRequest) {
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", "Não há chamados a serem atendidos."));
            return;
        }

        //SE NÃO ESTIVER SIDO INICIADO UMA CONVERÇA FICA MANDANDO A PRIMEEIRA MENSAGEM DO TEMPLATE
        if ($user->role_id === 4 && !$nextNedRequest) {
            $selectTemplateQuestions = DialogsTemplate::where('title', 'Egreja')->first();
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

        Log::info(json_encode($objectNextComands));

        //verificar se tem algum methodo para ser executado;
        if (is_object($objectNextComands)) {
            if (isset($objectNextComands->positive_response)) {
                $nextQuestion = DialogsQuestion::find($objectNextComands->positive_response);
                $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
                $this->updatePrayerRequest($objectNextComands->positive_response, $nextNedRequest, 1);
            }

            if (isset($objectNextComands->negative_response)) {
                $nextQuestion = DialogsQuestion::find($objectNextComands->negative_response);
                $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
                $this->updatePrayerRequest($objectNextComands->negative_response, $nextNedRequest, 1);
            }

            if ($objectNextComands->method) {
                $this->executeMethod($objectNextComands->method, $nextNedRequest, $dados['phone'], $dados['text']['message'], ($resultVerifyQuestion === 1));
            }
        } else {
            $nextQuestion = DialogsQuestion::find($currentQueestionId);
            switch ($objectNextComands) {
                case 'FORCE_STOP':
                    # code...
                    $nextQuestion = DialogsQuestion::find($nextQuestion->finish_dialog_question_id);
                    $this->closePrayerRequest($nextNedRequest);
                    $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $nextQuestion->question));
                    break;
                case 'NOT_IDENTIFY':
                    $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", "Não foi possivel entender a resposta"));
                default:
                    break;
            }
        }
        return response()->json(['message' => 'Dados do webhook recebidos com sucesso'], 200);
    }

    public function closePrayerRequest($nextNedRequest)
    {
        $nextNedRequest->status_id = 3;
        $nextNedRequest->update();
    }

    public function saveMessage($idPrayerRequest, $idDialogQuestion, $message)
    {
        HistoricalConversation::create([
            'prayer_requests_id' => $idPrayerRequest,
            'dialogs_questions_id' => $idDialogQuestion,
            'response' => $message
        ]);
    }

    public function createDefaultPrayerRequest($user, $dialogQuestionId, $reference = null)
    {
        $PrayerRequest = new PrayerRequest();
        $PrayerRequest->user_id = $user->id;
        $PrayerRequest->status_id = 1;
        $PrayerRequest->current_dialog_question_id = $dialogQuestionId;
        $PrayerRequest->reference = $reference;
        $PrayerRequest->save();
    }


    public function verifyRoleResponse($meessage, $idQuestion)
    {
        //verifica se existe um grupo de respostas para a questão;
        $existResponsesQuestion = $this->existResponsesQuestion($idQuestion);

        //se não existir um grupo de respostas
        if (!$existResponsesQuestion) {
            return null;
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
        Log::info($idQuestion);
        //pega todos os grupos de resposta para esta questão
        $grupsResponses = GroupQuestionsResponse::where('dialog_question_id', $idQuestion)->has('group_response')->get();

        Log::info(json_encode($grupsResponses));

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
        //trata o resultado da resposta;

        $currentQuestion = DialogsQuestion::find($currentQueestionId);
        $positiveQuestionResponse = $currentQuestion->next_dialog_question_id;
        $negativeQuestionResponse = $currentQuestion->next_negative_dialog_question_id;

        if (in_array($resultVerifyQuestion, [1])) {
            //caso role id da resposta for positiva e negativa
            return (object)["positive_response" => $positiveQuestionResponse, "method" => $currentQuestion->method ?? null];
        } else if (in_array($resultVerifyQuestion, [2])) {
            //caso role id da resposta for negativa
            return (object)["negative_response" => $negativeQuestionResponse, "method" => $currentQuestion->method ?? null];
        } else if (in_array($resultVerifyQuestion, [3])) {
            //caso exit
            return "FORCE_STOP";
        } else if (isset($currentQuestion->method)) {
            return (object)["method" => $currentQuestion->method ?? null];
        } else {
            //resposta não identificada
            return "NOT_IDENTIFY";
        }
    }

    public function updatePrayerRequest($questionId, $nextNedRequest, $statusId)
    {
        $nextNedRequest->current_dialog_question_id = $questionId;
        $nextNedRequest->status_id = $statusId;
        return $nextNedRequest->save();
    }








    //metodos para as questoes
    public function executeMethod($metod, $nextNedRequest, $actualyUserPhone = null, $message = null, $positiveOrNegativeResponse = null)
    {
        switch ($metod) {
            case 'send_message_to_volunteers':
                $zApiController = new ZApiController();
                if(!in_array($message,["1","2","SIM","NÃO","sim","nao","não"])){
                    $zApiController->sendMessage($actualyUserPhone, str_replace('\n', "\n", "Não foi possivel entender a resposta."));
                    return;
                }
                if (in_array($message,["2","NÃO","nao","não"])) {
                    $this->closePrayerRequest($nextNedRequest);
                    return;
                }
                //peagar todos os voluntarios da tabela e enviar 
                $voluntariers = User::where('role_id', 3)->get();
                foreach ($voluntariers as $obj) {
                    # code...
                    $originalPhone = preg_replace("/[^0-9]/", "", $obj['phone']);
                    sleep(1);
                    $existPrayerRequest = VolunteerRequest::where('user_id', $obj['id'])->where('status_id', 1)->exists();

                    if ($originalPhone === "5541989022440") {
                        if (!$existPrayerRequest) {
                            $user = User::find($obj['id']);
                            $selectTemplateQuestions =  DialogsTemplate::where('title', 'Egreja-Voluntary')->first();
                            $dialogQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->where('start', 1)->first();
                            $this->createDefaultPrayerRequest($user, $dialogQuestion->id, $nextNedRequest->id);
                            $zApiController->sendMessage($originalPhone, str_replace('\n', "\n", $dialogQuestion->question));
                        }
                    }
                }

                break;
            case 'acept_request_voluntary':
                if (!$positiveOrNegativeResponse) {
                    $this->closePrayerRequest($nextNedRequest);
                    return;
                }
                $user = User::where('phone', $actualyUserPhone)->first();
                //verifica see tem um usuario na chamada
                $payerRequeest = PrayerRequest::find($nextNedRequest->reference);
                $zApiController = new ZApiController();
                if (isset($payerRequeest->voluntary_id)) {
                    $zApiController->sendMessage($user->phone, str_replace('\n', "\n", "Este atendimento ja foi aceito por outro voluntário. \nObrigado."));
                    $nextNedRequest->status_id = 3;
                    $nextNedRequest->save();
                    break;
                }


                //pega o user que preeecisa dee ajuda
                $payer = User::find($payerRequeest->user_id);

                $zApiController->sendMessage($user->phone, str_replace('\n', "\n", "Voce aceitou atender ao atendimento.\nLigue para $payer->username\nTelefone: $payer->phone"));
                //setar o pedido de ajuda que foi aceito
                $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", "Um voluntário acabou de aceitar seu pedido fique atento ao seu telefone."));

                //por enquanto salva as duas conveersas como finalizada
                $nextNedRequest->status_id = 3;
                $nextNedRequest->update();

                $payerRequeest = PrayerRequest::find($nextNedRequest->reference);

                $payerRequeest->voluntary_id = $user->id;
                $payerRequeest->current_dialog_question_id = 15;
                $payerRequeest->update();

                break;
            case 'wait':
                $payer = User::find($nextNedRequest->user_id);
                $zApiController = new ZApiController();
                $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", "já possui um pedido de oração em aberto aguarde."));
                break;
            case 'save_dificult_contact':
                //este metodo recebe a resposta do cliente
                $payer = User::find($nextNedRequest->user_id);
                $currentDialogQuestion = DialogsQuestion::find($nextNedRequest->current_dialog_question_id);
                
                $zApiController = new ZApiController();
                if (!$message) {
                    $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", "Por favor digite uma mensagem válida."));
                    break;
                }
                //finaliza o contato
                $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", $currentDialogQuestion->next_dialog_question->question));
                $nextNedRequest->current_dialog_question_id = $currentDialogQuestion->next_dialog_question_id;
                $nextNedRequest->status_id = 3;
                $nextNedRequest->update();

                //enviar uma  demanda para  o pastor


                break;
            case 'save_sucess_contact':
                //este metodo recebe a resposta do cliente
                $payer = User::find($nextNedRequest->user_id);
                $currentDialogQuestion = DialogsQuestion::find($nextNedRequest->current_dialog_question_id);
                
                $zApiController = new ZApiController();
                if (!$message) {
                    $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", "Por favor digite uma mensagem válida."));
                    break;
                }
                //finaliza o contato
                $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", $currentDialogQuestion->next_dialog_question->question));
                $nextNedRequest->current_dialog_question_id = $currentDialogQuestion->next_dialog_question_id;
                $nextNedRequest->status_id = 3;
                $nextNedRequest->update();

                break;
            case 'check_acept_demand':
                $payer = User::find($nextNedRequest->user_id);
                $currentDialogQuestion = DialogsQuestion::find($nextNedRequest->current_dialog_question_id);
                $zApiController = new ZApiController();

                if (!$message) {
                    $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", "Não foi possivel entender a resposta."));
                    break;
                }

                if(in_array($message,['1','Atender demanda'])){
                    $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", $currentDialogQuestion->next_dialog_question->question));
                    $nextNedRequest->current_dialog_question_id = $currentDialogQuestion->next_dialog_question_id;
                }
                if(in_array($message,['2','Redirecionar'])){
                    $zApiController->sendMessage($payer->phone, str_replace('\n', "\n", $currentDialogQuestion->next_negative_dialog_question->question));
                    $nextNedRequest->current_dialog_question_id = $currentDialogQuestion->next_negative_dialog_question_id;
                }
                
                //finaliza o contato
                $nextNedRequest->status_id = 3;
                $nextNedRequest->update();

                break;
            default:
                # code...
                break;
        }
    }
}
