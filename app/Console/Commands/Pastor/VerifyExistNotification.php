<?php

namespace App\Console\Commands\Pastor;

use App\Http\Controllers\ZApiController;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Console\Command;

class VerifyExistNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-exist-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica se existe notificação pendente para o pastor e envia';

    private $zApiController;
    private $broterId;
    private $timeQuestionaryVoluntary;
    private $timeQuestionaryUser;
    private $userPastor;

    public function __construct()
    {
        parent::__construct();
        $this->zApiController = new ZApiController();
        $this->broterId = env('PASTOR_ID');
        $this->timeQuestionaryVoluntary = 10; //minutos
        $this->userPastor = User::find($this->broterId);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->sendPastorNotifications();
    }

    public function sendPastorNotifications()
    {
        //code...
        //verifica se o pastor tem alguma chama em aberto antes de iniciar uma nova conversa.
        $isAttending = Conversation::verifyUserInConversation($this->userPastor);

        $notification = Notification::where('user_id', $this->userPastor->id)
            ->where('type_notifications_id', 4)
            ->where('status_notifications_id', 1)
            ->first();

        if ($isAttending || !$notification) {
            return;
        }

        try {
            //boot abre uma conversa
            Conversation::openConversation($this->userPastor->id, 5, $notification->conversation_id);

            $data = [
                'username' => $notification->conversation->user->username ?? 'N/A',
                'voluntaryname' => $notification->conversation->voluntary->username ?? 'N/A'
            ];

            $this->sendInitialMessage($data, 5, $notification->user->getRawOriginal('phone'));
            //fecha a notificação
            Notification::closeNotification($notification);
        } catch (\Throwable $th) {
            //throw $th;
            Notification::failNotification($notification);
        }
    }

    public function sendInitialMessage($paramns, $templateId, $phone)
    {
        $message = Message::where('template_id', $templateId)->where('priority', 1)->first();
        $message = Utils::setDefaultNames($paramns, $message->message);
        $this->zApiController->sendMessage($phone, str_replace('\n', "\n", $message));
    }
}
