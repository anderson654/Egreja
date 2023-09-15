<?php

namespace App\Http\Controllers;

use App\Models\NeedRequest;
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
        // se não existir um pedido;
        if (!$nextNedRequest && $dados['text']['message'] != '1') {
            $zApiController->sendMessage($dados['phone'], "Olá seja bem vindo a aplicação e-greja.\n1-precisa de ajuda\n2-não obrigado");
            return;
        } else if (!$nextNedRequest && $dados['text']['message'] == '1') {
            //cria uma linha em need_reuquests
            $needrequest = new NeedRequest();
            $needrequest->user_id = $user->id;
            $needrequest->status_id = 1;
            $needrequest->current_dialog_id = 1;
            $needrequest->save();
            $zApiController->sendMessage($dados['phone'], "Ok enviamos o seu pedido para os nossos voluntarios logo vc recebera um contato.");
        }

        
        //se existir um pedido
        if ($nextNedRequest) {
            switch ($nextNedRequest->current_dialog_id) {
                case 1:
                    $zApiController->sendMessage($dados['phone'], "Verificamos que vc possui um peido de ajuda em aberto\n1-Cancelar pedido de ajuda.");
                    # code...
                    break;

                default:
                    # code...
                    break;
            }
        }


        return response()->json(['message' => 'Dados do webhook recebidos com sucesso'], 200);
    }
}
