<?php

namespace App\Models;

use App\Models\WhatsApp\GroupsResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsesToGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'response',
        'group_responses_id'
    ];

    public function group_response()
    {
        return $this->hasOne(GroupsResponse::class, 'id', 'group_responses_id');
    }

    /**
     * @param string $message mensagem recebida do z-api
     * @param int $idQuestion id da  questão para ser verificado o role da resposta
     * @return ResponsesToGroup retorna a primeira resposta encontrada no grupo  junto com with group_response que possui o role
     */
    public static function verifyRoleResponse($message, $idQuestion)
    {

        $grupsResponses = GroupQuestionsResponse::where('dialog_question_id', $idQuestion)->has('group_response')->get();
        $idsGroups = $grupsResponses->pluck('groups_responses_id');
        //verifica se em algun grupo existe uma resposta com esse valor;
        return ResponsesToGroup::where('response', $message)->whereIn('group_responses_id', $idsGroups)->with('group_response')->first();
    }
}
