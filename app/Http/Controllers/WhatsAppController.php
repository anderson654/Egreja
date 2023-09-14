<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\ZApiController;

class WhatsAppController extends Controller
{
    public function enviarMensagemPersonalizada(Request $request)
{
    // Lógica para personalizar a mensagem aqui, se necessário
    $phone = $request->input('phone');
    $message = $request->input('message');
    // Chama o método enviarMensagem para enviar a mensagem
    $zApi = new ZApiController;
    return $zApi->sendMessage($phone, $message);
}
}
