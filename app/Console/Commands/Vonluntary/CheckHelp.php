<?php

namespace App\Console\Commands\Vonluntary;

use App\Http\Controllers\ZApiController;
use App\Http\Controllers\ZApiWebHookController;
use App\Models\DialogsQuestion;
use App\Models\PrayerRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckHelp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-help';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando verifica os chamados em aberto a cada 1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prayerRequests = PrayerRequest::where('status_id', 1)->has('prayer')->get();
        //
        foreach ($prayerRequests as $prayerRequest) {
            //caso passe de 30 min e ninguem atendeu fechar o chamado e enviar uma mensagem de desculpa
            // $this->closePrayer30Minuts($prayerRequest);

            //verificar se alguem ja aceitou o chamado e fechar para todos enviando uma mensagem
            // $this->closePrayer30Minuts($prayerRequest);

            //apos 10 min enviar avaliação.
            $this->sendAvaliable($prayerRequest);
        }
    }

    public function closePrayer30Minuts($prayerRequest)
    {
        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(30);
        if ($limitTime < Carbon::now()) {
            $prayerRequest->status_id = 3;
            $prayerRequest->update();
            //pegar todos os pedidos relacionados e fechar
            $voluntaryPrayerRequests = PrayerRequest::where('reference', $prayerRequest->id)->get();
            foreach ($voluntaryPrayerRequests as $voluntaryPrayerRequest) {
                # code...
                $voluntaryPrayerRequest->status_id = 3;
                $voluntaryPrayerRequest->update();
            }
        }
    }

    public function sendAvaliable($prayerRequest){
        $zApiController = new ZApiController();
        //verifiaca se existe um voluntario na chamada
        if(!isset($prayerRequest->voluntary_id)){
            return;
        }
        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(10);
        //verificar se ele não tem chamadas em aberto.
        if ($limitTime < Carbon::now() ) {
            //salvar e enviar o template para o user.
            $zapiWebHoockController = new ZApiWebHookController();
            //users
            $user = User::find($prayerRequest->voluntary_id);
            //questão
            $firstQuestion = DialogsQuestion::where('dialog_template_id', 3)->where('start', 1)->first();
            $zapiWebHoockController->createDefaultPrayerRequest($user,$firstQuestion->id);

            //apos criar enviar a mensagem.
            $zApiController->sendMessage($user->getRawOriginal('phone'),str_replace('\n', "\n", $firstQuestion->question));
        }
    }

}
