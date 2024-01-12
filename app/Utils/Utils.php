<?php

namespace App\Utils;

use App\Models\User;
use App\Models\UsersTest;

class Utils
{
    /**
     * @param object $paramns objeto com parametros
     * Aceita os seguintes parametros.
     * - username {{REQUESTER_NAME}}.
     * - voluntaryname {{VOLUNTEER_NAME}}.
     * - phone {{PHONE}}.
     * @param string $message uma string com a mensagem.
     * @return string $message
     */
    public static function setDefaultNames($paramns, $message)
    {
        $finalMessage = $message;
        $util = new Self();
        foreach ($paramns as $key => $value) {
            # code...
            $finalMessage = $util->setParan($key, $value, $finalMessage);
        }

        return $finalMessage;
    }

    public function setParan($key, $value, $message)
    {
        switch ($key) {
            case 'username':
                return str_replace("{{REQUESTER_NAME}}", $value, $message);
            case 'voluntaryname':
                # code...
                return str_replace("{{VOLUNTEER_NAME}}", $value, $message);
            case 'phone':
                # code...
                return str_replace("{{PHONE}}", $value, $message);
            case 'id':
                # code...
                return str_replace("{{ID}}", $value, $message);
            case 'datetime':
                # code...
                return str_replace("{{DATETIME}}", $value, $message);
            case 'number_notification':
                # code...
                return str_replace("{{NUMBER_NOTIFICATION}}", $value, $message);
            default:
                # code...
                break;
        }
    }

    /**
     * Esta função retorna o numero de telefone dos uses test sem o mutator aplicado.
     * @return array 
     */
    public static function getNumbersTest()
    {
        $idUsers = UsersTest::pluck('user_id');
        $arrayPhones = User::whereIn('id', $idUsers)->get()->append('phone_original')->pluck('phone_original')->toArray();
        return $arrayPhones;
    }
}
