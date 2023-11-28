<?php

namespace App\Console\Commands\Vonluntary;

use App\Http\Controllers\WhatsApp\DialogsTemplatesController;
use App\Http\Controllers\ZApiController;
use App\Http\Controllers\ZApiWebHookController;
use App\Models\DialogsQuestion;
use App\Models\PrayerRequest;
use App\Models\SideDishes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

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
        $prayerRequests = PrayerRequest::whereIn('status_id', [1, 2, 3])->has('prayer')->has('voluntary')->get();
        // $prayerRequests = PrayerRequest::find(238);
        // $this->sendAvaliable($prayerRequests);
        // return;
        //
        foreach ($prayerRequests as $prayerRequest) {
            //caso passe de 30 min e ninguem atendeu fechar o chamado e enviar uma mensagem de desculpa
            $this->closePrayer30Minuts($prayerRequest);

            //verificar se alguem ja aceitou o chamado e fechar para todos enviando uma mensagem
            // $this->closePrayer30Minuts($prayerRequest);

            //apos 10 min enviar avaliação.
            $this->sendAvaliable($prayerRequest);

            //apos duas horas enviar questionario para o irmão
            // $this->sendAvaliableBrother($prayerRequest);
        }



        //verifica se existe algum side_dishes com message_received = null
        // $sideDishes = SideDishes::whereNull('message_send')->get();
        // foreach ($sideDishes as $sideDishe) {
        //     # code...
        //     //verificar se o pastor tem alguma chamada em aberto.
        //     $prayerRequest = PrayerRequest::where(function ($query) use ($sideDishe) {
        //         $query->where('user_id', $sideDishe->responsible_user_id)
        //             ->orWhere('voluntary_id', $sideDishe->responsible_user_id);
        //     })->where('status_id', 1)->exists();

        //     if ($prayerRequest) {
        //         return;
        //     }
        //     //enviar mensagem
        //     $request = new Request();
        //     $variables = [
        //         "user_name"  => User::find($sideDishe->responsible_user_id)['username'],
        //         "voluntary_name"  =>  User::find($sideDishe->user_id)['username'],
        //     ];
        //     $request->merge(["user_id" => $sideDishe->responsible_user_id, "template_id" => 6, "variables" => $variables]);

        //     $dialogTemplatesController = new DialogsTemplatesController();
        //     $dialogTemplatesController->sendTemplate($request);

        //     $sideDishe->message_send = true;
        //     $sideDishe->save();
        // }
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

    public function sendAvaliable($prayerRequest)
    {
        //verificar se a chamada na questão foi aberto.
        //verifiaca se existe um voluntario na chamada.
        if ($prayerRequest->questionary_brother || !isset($prayerRequest->voluntary_id)) {
            return;
        }
        $zApiController = new ZApiController();
        //10min
        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(2);
        //verificar se ele não tem chamadas em aberto.
        if ($limitTime < Carbon::now()) {
            //users
            $user = User::find($prayerRequest->voluntary_id);
            //questão
            $firstQuestion = DialogsQuestion::where('dialog_template_id', 3)->where('start', 1)->first();

            PrayerRequest::newPrayerRequest($user, $firstQuestion);
            //setar o user na mensagem
            $message = str_replace("{{REQUESTER_NAME}}", $prayerRequest->user->username, $firstQuestion->question);
            $message = str_replace("{{VOLUNTEER_NAME}}", $prayerRequest->voluntary->username, $message);
            //apos criar enviar a mensagem.
            $zApiController->sendMessage($user->getRawOriginal('phone'), str_replace('\n', "\n", $message));
            $prayerRequest->questionary_brother = 1;
            $prayerRequest->update();
        }
    }

    public function sendAvaliableBrother($prayerRequest)
    {
        //ajustes en vez do user o voluntario setado.


        //verificar se a chamada na questão foi aberto.
        //verifiaca se existe um voluntario na chamada.
        if ($prayerRequest->questionary_user || !isset($prayerRequest->user_id)) {
            return;
        }
        $zApiController = new ZApiController();
        // ->addHours(2)
        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(5);
        //verificar se ele não tem chamadas em aberto.
        if ($limitTime < Carbon::now()) {
            //users
            $user = User::find($prayerRequest->user_id);
            //salvar e enviar o template para o user.
            $zapiWebHoockController = new ZApiWebHookController($user);
            //questão
            $firstQuestion = DialogsQuestion::where('dialog_template_id', 5)->where('start', 1)->first();
            $zapiWebHoockController->createDefaultPrayerRequest($user, $firstQuestion->id);
            //setar o user na mensagem
            $message = str_replace("{{REQUESTER_NAME}}", $prayerRequest->user->username, $firstQuestion->question);
            $message = str_replace("{{VOLUNTEER_NAME}}", $prayerRequest->voluntary->username, $message);
            //apos criar enviar a mensagem.
            $zApiController->sendMessage($user->getRawOriginal('phone'), str_replace('\n', "\n", $message));
            $prayerRequest->questionary_user = 1;
            $prayerRequest->update();
        }
    }
}
