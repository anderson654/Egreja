<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZApiWebHookController extends Controller
{
    public function getStatusMessage(Request $request)
    {
        Log::info("-------WebhoockLaravel---------");
        $dados = $request->all();

        //se não existir criar um usuario
        $user = User::where('phone',$dados['phone'])->first();
        if(!$user){
            $newUser = new User();
            $newUser->phone = $dados['phone'];
            $newUser->username = $dados['senderName'] ?? 'anonimo';
            $newUser->email = 'e_greja_'.rand(1, 1000000000).'@gmail.com';
            $newUser->password = '123456789';
            $newUser->role_id = 4;
            if(!$newUser->save()){
                Log::info('Erro ao salvar User: '. $dados['phone']);
            }
        }

        $zApiController = new ZApiController();
        $zApiController->sendMessage($dados['phone'],'Olá seja bem vindo a aplicação e-greja.');

        return response()->json(['message' => 'Dados do webhook recebidos com sucesso'], 200);
    }
}
