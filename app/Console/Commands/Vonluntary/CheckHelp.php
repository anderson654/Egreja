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

    private $zApiController;
    private $broterId;

    public function __construct()
    {
        parent::__construct();
        $this->zApiController = new ZApiController();
        $this->broterId = 65;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return;
        $prayerRequests = PrayerRequest::whereIn('status_id', [1, 2, 3, 6])->has('prayer')->has('voluntary')->get();
        foreach ($prayerRequests as $prayerRequest) {
            //envia a mensagem para o pastor que não foi atendida
            if ($prayerRequest->status_id == 6) {
                $this->alertBroter($prayerRequest);
                return;
            }
            //caso passe de 30 min e ninguem atendeu fechar o chamado e enviar uma mensagem de desculpa
            $this->closePrayer30Minuts($prayerRequest);

            //apos 10 min enviar avaliação.
            $this->sendAvaliable($prayerRequest);

            //apos duas horas enviar questionario para o irmão
            $this->sendAvaliableBrother($prayerRequest);
        }





        // pegar todos os prayer requests que tenhão o status_id = 6
        // verifica se existe algum side_dishes com message_received = null
        $sideDishes = SideDishes::whereNull('message_send')->with('user')->get();
        foreach ($sideDishes as $sideDishe) {
            # code...
            //verificar se o pastor tem alguma chamada em aberto.
            $prayerRequests = PrayerRequest::whereIn('status_id', [1, 2, 4, 6])->where('user_id', $this->broterId)->exists();
            if ($prayerRequests) {
                return;
            }

            //enviar mensagem
            $user = User::find($this->broterId);
            $dialogQuestion = DialogsQuestion::where('dialog_template_id', 6)->where('priority', 1)->first();
            PrayerRequest::newPrayerRequest($user, $dialogQuestion, $prayerRequest->id);
            $message = $this->setDefaultNames(['username' => $sideDishe->user->username, 'voluntaryname' => $user->username], $dialogQuestion->question);
            $this->zApiController->sendMessage($user->getRawOriginal('phone'), str_replace('\n', "\n", $message));

            $sideDishe->message_send = true;
            $sideDishe->save();
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

    public function sendAvaliable($prayerRequest)
    {
        //para iniciar um outrodialogo feche as prayeer requests atuais
        //verificar aqui se o  status é 3

        //verificar se a chamada na questão foi aberto.
        //verifiaca se existe um voluntario na chamada.
        if ($prayerRequest->questionary_brother || !isset($prayerRequest->voluntary_id)) {
            return;
        }
        //10min
        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(2);
        //verificar se ele não tem chamadas em aberto.
        if ($limitTime < Carbon::now()) {
            //users
            $user = User::find($prayerRequest->voluntary_id);
            //questão
            $firstQuestion = DialogsQuestion::where('dialog_template_id', 3)->where('start', 1)->first();

            PrayerRequest::newPrayerRequest($user, $firstQuestion, $prayerRequest->id);
            //setar o user na mensagem
            $message = $this->setDefaultNames(['username' =>  $prayerRequest->user->username, 'voluntaryname' => $prayerRequest->voluntary->username], $firstQuestion->question);
            //apos criar enviar a mensagem.
            $this->zApiController->sendMessage($user->getRawOriginal('phone'), str_replace('\n', "\n", $message));
            $prayerRequest->status_id = 3;
            $prayerRequest->questionary_brother = 1;
            $prayerRequest->update();
        }
    }

    public function sendAvaliableBrother($prayerRequest)
    {
        //verificar se a chamada na questão foi aberto.
        //verifiaca se existe um voluntario na chamada.
        if ($prayerRequest->questionary_user || !isset($prayerRequest->user_id)) {
            return;
        }
        // ->addHours(2)
        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(5);
        //verificar se ele não tem chamadas em aberto.
        if ($limitTime < Carbon::now()) {
            //users
            $user = User::find($prayerRequest->user_id);
            //salvar e enviar o template para o user.
            //questão
            $firstQuestion = DialogsQuestion::where('dialog_template_id', 5)->where('start', 1)->first();
            PrayerRequest::newPrayerRequest($user, $firstQuestion, $prayerRequest->id);
            //setar o user na mensagem
            $message = $this->setDefaultNames(['username' =>  $prayerRequest->user->username, 'voluntaryname' => $prayerRequest->voluntary->username], $firstQuestion->question);
            //apos criar enviar a mensagem.
            $this->zApiController->sendMessage($user->getRawOriginal('phone'), str_replace('\n', "\n", $message));
            $prayerRequest->questionary_user = 1;
            $prayerRequest->update();
        }
    }

    public function alertBroter($prayerRequest)
    {
        //verifica se o user tem algo em aberto;
        //65 pastor
        $prayerRequests = PrayerRequest::whereIn('status_id', [1, 2, 4, 6])->where('user_id', $this->broterId)->exists();
        if ($prayerRequests) {
            return;
        }

        $limitTime = Carbon::parse($prayerRequest->created_at->toString())->addMinutes(5);
        if ($limitTime < Carbon::now()) {
            $dialogQuestion = DialogsQuestion::where('dialog_template_id', 4)->where('priority', 1)->first();
            $message = $this->setDefaultNames(['username' =>  $prayerRequest->user->username, 'voluntaryname' => $prayerRequest->voluntary->username], $dialogQuestion->question);

            //apos criar enviar a mensagem.
            $user = User::find($this->broterId);
            PrayerRequest::newPrayerRequest($user, $dialogQuestion, $prayerRequest->id);
            $this->zApiController->sendMessage($user->getRawOriginal('phone'), str_replace('\n', "\n", $message));
        }
    }

    public function setDefaultNames($paramns, $question)
    {
        $message = str_replace("{{REQUESTER_NAME}}", $paramns['username'], $question);
        $message = str_replace("{{VOLUNTEER_NAME}}", $paramns['voluntaryname'], $message);
        return $message;
    }
}
