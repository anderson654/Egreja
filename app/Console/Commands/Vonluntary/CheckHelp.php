<?php

namespace App\Console\Commands\Vonluntary;

use App\Models\PrayerRequest;
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
            $this->closePrayer30Minuts($prayerRequest);

            //verificar se alguem ja aceitou o chamado e fechar para todos enviando uma mensagem
            // $this->closePrayer30Minuts($prayerRequest);

            //apos 10 min enviar avaliação.
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

    // public function sendAvaliable(){

    // }
}
