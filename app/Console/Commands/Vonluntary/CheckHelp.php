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
    private $userPastor;

    public function __construct()
    {
        parent::__construct();
        $this->zApiController = new ZApiController();
        $this->broterId = 65;

        // $this->timeQuestionaryVoluntary = 10; //minutos
        // $this->timeQuestionaryUser = (60*24)*2; //minutos
        $this->timeQuestionaryVoluntary = 5; //minutos
        $this->timeQuestionaryUser = 5; //minutos

        $this->userPastor = User::find(65);
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

        //verifica as notificaçoes do pastor
        $this->sendPastorNotifications();



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
                //boot abre uma conversa
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
                //boot abre uma conversa
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

    /**
     * Verifica se existe notificaçoes para o pastor e envia para ele.
     */
    public function sendPastorNotifications()
    {
        //verifica se o pastor tem alguma chama em aberto antes de iniciar uma nova conversa.
        $isAttending = User::verifyUserInAttending($this->userPastor->id);

        $notification = Notification::where('user_id', $this->userPastor->id)
            ->where('type_notifications_id', 3)
            ->where('status_notifications_id', 2)
            ->first();

        if ($isAttending || !$notification) {
            return;
        }

        //boot abre uma conversa
        Conversation::openConversation($this->userPastor->id, 6, $notification->conversation_id);

        $data = [
            'username' => $notification->conversation->user->username,
            'voluntaryname' => $this->userPastor->username
        ];

        $this->sendInitialMessage($data, 6, $notification->user->getRawOriginal('phone'));
        //fecha a notificação
        Notification::aceptedNotification($notification);
    }


    public function sendInitialMessage($paramns, $templateId, $phone)
    {
        $message = Message::where('template_id', $templateId)->where('priority', 1)->first();
        $message = Utils::setDefaultNames($paramns, $message->message);
        $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message));
    }
}
