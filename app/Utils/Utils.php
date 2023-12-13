<?php

namespace App\Utils;

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
            $finalMessage = $util->setParan($key,$value,$finalMessage);
        }

        return $finalMessage;
    }

    public function setParan($key,$value,$message){
        switch ($key) {
            case 'username':
               return str_replace("{{REQUESTER_NAME}}", $value, $message);
            case 'voluntaryname':
                # code...
                return str_replace("{{VOLUNTEER_NAME}}", $value, $message);
            case 'phone':
                # code...
                return str_replace("{{PHONE}}", $value, $message);
            default:
                # code...
                break;
        }
    }
}
