<?php

namespace App\Http\Controllers;

use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\GroupQuestionsResponse;
use App\Models\NeedRequest;
use App\Models\ResponsesToGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZApiWebHookController extends Controller
{
    public function getStatusMessage(Request $request)
    {
        Log::info("-------WebhoockLaravel---------");
        $dados = $request->all();

        //vrificar se o dado recebido de isGroup é falso se sim é uma pessoa enviando mensagem;
        if ($dados['isGroup']) {
            return;
        }
        if ($dados['phone'] != '554195640242') {
            return;
        }
        //se não existir criar um usuario
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

        
        //verificar se este telefone tem um chamado em aberto
        $nextNedRequest = NeedRequest::where('user_id', $user->id)->where('status_id', '!=', 3)->first();
        
        $zApiController = new ZApiController();
        //SE NÃO ESTIVER SIDO INICIADO UMA CONVERÇA
        if (!$nextNedRequest) {
            $selectTemplateQuestions =  DialogsTemplate::where('title', 'Egreja')->first();
            $dialogQuestion = DialogsQuestion::where('dialog_template_id', $selectTemplateQuestions->id)->orderBy('priority', 'ASC')->first();
            
            $this->createDefaultNeedRequest($user);
            $zApiController->sendMessage($dados['phone'], str_replace('\n', "\n", $dialogQuestion->question));
            return;
        }
        //SE JA ESTIVER SIDO INICIADO UMA CONVERÇA
        if ($nextNedRequest) {
            $dialogQuestions = GroupQuestionsResponse::where('dialog_question_id',$nextNedRequest->current_dialog_question_id)->get();
            $responsesQuestions = ResponsesToGroup::whereIn('group_questions_response_id',$dialogQuestions->pluck('id'))->get();
            //coloca todas as respostas positivas em um array exemplo;
            $positiveResponse = $responsesQuestions->pluck('response')->toArray();

            //verifica se a mensagem enviada pelo usuario bate com alguma dessas;
            if(in_array($dados['text']['message'],$positiveResponse)){
                Log::info('A mensagem bateu');
                return;
            }else{
                Log::info('não entendi por favor repita');
                return;
            }
        }
        
        return response()->json(['message' => 'Dados do webhook recebidos com sucesso'], 200);
    }

    public function createDefaultNeedRequest($user)
    {
        $needrequest = new NeedRequest();
        $needrequest->user_id = $user->id;
        $needrequest->status_id = 1;
        $needrequest->current_dialog_question_id = 1;
        $needrequest->save();
    }
}
