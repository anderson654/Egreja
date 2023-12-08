<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\DialogsQuestion;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;

class VoluntaryController extends Controller
{
    private $zApiController;
    private $prayerRequests;
    private $user;
    private $date;
    private $question;

    /**
     * @param User $user Recebe o usuario
     */
    public function __construct($user)
    {
        $this->zApiController = new ZApiController();
        $this->user = $user;
        $this->prayerRequests = PrayerRequest::where('user_id', $user->id)->whereIn('status_id', [1, 2, 4, 5])->first();
        if ($this->prayerRequests && isset($this->prayerRequests->current_dialog_question_id)) {
            $this->question =  DialogsQuestion::find($this->prayerRequests->current_dialog_question_id);
        }
    }



    //controla as chamadas do voluntario.
    /**
     * @param object $date Dados do z-api
     * @return void
     */
    public function initChanelVoluntary($date)
    {
        //regra adicional verificar se vc esta atendendo alguem
        $isAttending = $this->checkIsAttending();
        if($isAttending){
            $prayer = User::find($isAttending->user_id);
            $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", "Você já aceitou atender a um pedido de oração.\nLigue para $prayer->username\nTelefone: $prayer->phone"));
            return;
        }
    
        //se não existir nenhuma chamada em aberto retornar
        if (!$this->prayerRequests) {
            $this->zApiController->sendMessage($date['phone'], str_replace('\n', "\n", "Não há chamados a serem atendidos."));
            return;
        }
       
        $this->date = $date;
        //salva a mensagem no historico.
        HistoricalConversation::saveMessage($this->prayerRequests->id, $this->question->id, $this->date['text']['message']);

        //inicias as funçoes padrão
        $defaultFunctionsController = new DefaultFunctionsController($this->user, $date, $this->prayerRequests, $this->question);
        $defaultFunctionsController->nextDialogQuestion();
    }


    /**
     * @param string $message recebe uma questão
     * @param DialogsQuestion $question  recebe uma questão opcional
     */
    public function sendMessageAllVoluntaries($message, $dialogQuestion = null)
    {
        //pegar todos menos aqueles que possuem dialogos em aberto;
        //existe 1,2,4 em aberto?
        $voluntaries = User::getVoluntariesNotAttending();

        foreach ($voluntaries as $voluntary) {
            # code...
            // if ($voluntary->phone === "5541995640242") {
                if ($dialogQuestion) {
                    PrayerRequest::newPrayerRequest($voluntary, $dialogQuestion, $this->prayerRequests->id, 5);
                }
                $phone = $voluntary->getOriginal('phone');
                $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message));
            // }
        }
    }

    /**
     * Verifica  se o voluntario esta em um atendimento;
     * @return  PrayerRequest
     */
    public function checkIsAttending(){
        $prayerRequest =  PrayerRequest::where('status_id',2)->where('voluntary_id',$this->user->id)->first();
        return $prayerRequest;
    }
}
