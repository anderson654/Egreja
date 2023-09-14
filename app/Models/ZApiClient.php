<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZApiClient extends Model
{
    use HasFactory;
    private $apiKey;
    private $secretKey;
    private $baseUrl = 'https://api.z-api.io';

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = env('ZAPI_SECRET_KEY');
    }

    public function enviarMensagem($telefone, $mensagem)
    {
        // Implemente a lógica para enviar mensagens usando o Z-API aqui
    }
}
