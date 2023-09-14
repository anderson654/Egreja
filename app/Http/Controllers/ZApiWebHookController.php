<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZApiWebHookController extends Controller
{
    public function getStatusMessage(Request $request)
    {
        // Obtenha os dados recebidos do webhook
        $dados = $request->all();

        Log::info($dados);

        // Processar os dados do webhook, por exemplo, atualizar o status das mensagens no banco de dados

        // Retorne uma resposta adequada para o webhook
        return response()->json(['message' => 'Dados do webhook recebidos com sucesso'], 200);
    }
}
