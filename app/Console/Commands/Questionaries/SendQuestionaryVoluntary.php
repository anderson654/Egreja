<?php

namespace App\Console\Commands\Questionaries;

use App\Http\Controllers\Channels\DefaultFunctionsController;
use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendQuestionaryVoluntary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-questionary-voluntary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia e verifica questionario de voluntario';

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

        $this->timeQuestionaryVoluntary = 10; //minutos
        $this->userPastor = User::find($this->broterId);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //reenvia formulario para voluntario
        $this->resendQuestionaryVoluntary();
        //envia formulario para voluntario
        $this->sendQuestionaryVoluntary();
        //finaliza o formulario não respondido e envia para o pastor
        $this->closeConversation();
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
            $isAttending = User::verifyUserInAttending($notification->user_id);
            if (!$isAttending) {
                //boot abre uma conversa
                $conversation = Conversation::openConversation($notification->user_id, 3, $notification->conversation_id);
                //envia a primeira mensagem
                $data = [
                    'username' => $notification->conversation->user->username,
                    'voluntaryname' => $notification->user->username
                ];

                $this->sendInitialMessage($data, 3, $notification->user->getRawOriginal('phone'));
                //fecha a notificação
                Notification::aceptedNotification($notification);
                $conversation->number_of_notifications = 1;
                $conversation->update();
            }
        }
    }

    public function resendQuestionaryVoluntary()
    {
        $conversations = Conversation::where('messages_id', 9)
            ->where('status_conversation_id', 1)
            ->where('number_of_notifications', '<', 3)
            ->get();

        foreach ($conversations as $conversation) {
            $referenceConversation = Conversation::where('id', $conversation->reference_conversation_id)
                ->with('user')
                ->with('voluntary')
                ->first();
            # code...
            $data = [
                'username' => $referenceConversation->user->username ?? 'N/A',
                'voluntaryname' => $referenceConversation->voluntary->username ?? 'N/A'
            ];

            $this->sendInitialMessage($data, 3, $referenceConversation->voluntary->getRawOriginal('phone'));
            $conversation->increment('number_of_notifications');
            $conversation->save();
        }
    }

    public function sendInitialMessage($paramns, $templateId, $phone)
    {
        $message = Message::where('template_id', $templateId)->where('priority', 1)->first();
        $message = Utils::setDefaultNames($paramns, $message->message);
        $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message));
    }

    //fecha a converça porque não existiu uma resposta
    public function closeConversation()
    {
        $conversations = Conversation::where('messages_id', 9)
            ->where('status_conversation_id', 1)
            ->where('number_of_notifications', 3)
            ->whereDate('created_at', '<=', Carbon::now()->subMinutes($this->timeQuestionaryVoluntary))
            ->get();

        if (!$conversations) {
            return;
        }

        foreach ($conversations as $conversation) {
            //enviar a proxima e fechar a chamada
            $defaultFunctionController = new DefaultFunctionsController($conversation->user, null, $conversation);
            $defaultFunctionController->manualyNextQuestion(6);
            $defaultFunctionController->closePrayerRequest();
            Notification::openNotification($this->userPastor, $conversation->reference_conversation, 4, 1);
        }
    }
}
