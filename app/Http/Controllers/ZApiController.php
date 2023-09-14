<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZApiClient;

use GuzzleHttp\Client;

class ZApiController extends Controller
{
    private $zapiClient;

    public function __construct()
    {
        $this->zapiClient = new Client(); // Crie um cliente Guzzle para as solicitações HTTP
    }

    public function sendMessage($phone, $message)
    {

        $ZAPI_API_KEY = env('ZAPI_API_KEY');
        $ZAPI_SECRET_KEY = env('ZAPI_SECRET_KEY');

        // Construa o corpo da solicitação
        $body = [
            'phone' => $phone,
            'message' => $message,
        ];

        // Use a instância $zapi para enviar mensagens WhatsApp
        // Faça a solicitação POST para a API Z-API
        try {
            $response = $this->zapiClient->post("https://api.z-api.io/instances/$ZAPI_API_KEY/token/$ZAPI_SECRET_KEY/send-text", [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody());

            // Você pode processar a resposta da API aqui, por exemplo, retornar um JSON de sucesso
            return response()->json([
                'zaapId' => $data->zaapId,
                'messageId' => $data->messageId,
                'id' => $data->id,
            ]);
        } catch (\Exception $e) {
            // Lidar com erros, por exemplo, retornar um JSON de erro
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
