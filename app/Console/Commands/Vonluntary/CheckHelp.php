<?php

namespace App\Console\Commands\Vonluntary;

use App\Http\Controllers\Channels\VoluntaryController;
use App\Http\Controllers\WhatsApp\DialogsTemplatesController;
use App\Http\Controllers\ZApiController;
use App\Http\Controllers\ZApiWebHookController;
use App\Models\Conversation;
use App\Models\DialogsQuestion;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PrayerRequest;
use App\Models\SideDishes;
use App\Models\User;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    private $zApiController;
    private $broterId;
    private $timeQuestionaryVoluntary;
    private $timeQuestionaryUser;

    public function __construct()
    {
        parent::__construct();
        $this->zApiController = new ZApiController();
        $this->broterId = 65;

        $this->timeQuestionaryVoluntary = 2; //minutos
        $this->timeQuestionaryUser = 5; //minutos
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //envia a chamada que esta na fila para os voluntarios.
        $this->sendMessageInQueue();

        //envia o questionario para os voluntarios.
        $this->sendQuestionaryVoluntary();


        //envia o questionario para os usuarios.
        $this->sendQuestionaryUser();



        $conversations = Conversation::get();
        foreach ($conversations as $conversation) {
            # code...
            $limitTime = Carbon::parse($conversation->updated_at->toString())->addMinutes(5);
            if ($limitTime < Carbon::now()) {
                $conversation->status_conversation_id = 3;
                $conversation->update();
            }
        }
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

    /**
     * Verifica se tem algum chamado do tipo 1(solicitação de atendimento).
     */
    public function sendMessageInQueue()
    {
        //pega a primeira notificação na fila.
        $firstNotification = Notification::where('status_notifications_id', 2)
            ->where('type_notifications_id', 1)
            ->has('pending_conversation')
            ->with('conversation')
            ->first();

        if (!$firstNotification) {
            return;
        }

        Log::info(json_encode($firstNotification->conversation));

        //pega a convereça
        $conversation = Conversation::find($firstNotification->conversation_id);
        //mendagem a ser enviada
        $nextMessage = Message::where('template_id', 2)->where('priority', 1)->first();

        $voluntaryController = new VoluntaryController($conversation->user);
        $voluntaryController->sendMessageAllVoluntaries($nextMessage, $conversation);

        //depois de enviado a mensagem fechar a notificação atual
        $firstNotification->status_notifications_id = 1;
        $firstNotification->update();
    }



    //colocar o tempo minimo para acontecer o envio dos formularios.
    public function sendQuestionaryVoluntary()
    {
        //code...
        $notifications = Notification::where('type_notifications_id', 2)
            ->where('status_notifications_id', 2)
            ->whereHas('user', function ($query) {
                $query->where('role_id', 3);
            })
            ->whereDate('created_at', '<=', Carbon::now()->subMinutes($this->timeQuestionaryVoluntary))
            ->get();

        foreach ($notifications as $notification) {
            # code...
            //teste;
            // if ($notification->user_id !== 7) {
            //     return;
            // }


            $isAttending = User::verifyUserInAttending($notification->user_id);
            if (!$isAttending) {
                //boot abre uma converça
                Conversation::openConversation($notification->user_id, 3, $notification->conversation_id);
                //envia a primeira mensagem
                $data = [
                    'username' => $notification->conversation->user->username,
                    'voluntaryname' => $notification->user->username
                ];
                $this->sendInitialMessage($data, 3, $notification->user->getRawOriginal('phone'));
                //fecha a notificação
                Notification::aceptedNotification($notification);
            }
        }
    }

    public function sendQuestionaryUser()
    {

        $notifications = Notification::where('type_notifications_id', 2)
            ->where('status_notifications_id', 2)
            ->whereHas('user', function ($query) {
                $query->where('role_id', 4);
            })
            ->whereDate('created_at', '<=', Carbon::now()->subMinutes($this->timeQuestionaryUser))
            ->get();
        foreach ($notifications as $notification) {
            # code...
            $isAttending = User::verifyUserInAttending($notification->user_id);
            if (!$isAttending) {
                //boot abre uma converça
                Conversation::openConversation($notification->user_id, 4, $notification->conversation_id);
                //envia a primeira mensagem
                $data = [
                    'username' => $notification->user->username,
                ];
                $this->sendInitialMessage($data, 4, $notification->user->getRawOriginal('phone'));
                //fecha a notificação
                Notification::aceptedNotification($notification);
            }
        }
    }


    public function sendInitialMessage($paramns, $templateId, $phone)
    {
        $message = Message::where('template_id', $templateId)->where('priority', 1)->first();
        $message = Utils::setDefaultNames($paramns, $message->message);
        $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message));
    }
}
