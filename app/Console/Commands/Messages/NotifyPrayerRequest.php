<?php

namespace App\Console\Commands\Messages;

use App\Http\Controllers\Channels\VoluntaryController;
use App\Models\Conversation;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyPrayerRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-prayer-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando envia notificação para os voluntarios a cada 30 segundos caso nenhum tenha aceitado.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //foi atendida?
        //maximo de notificaçoes 3;
        $prayerRequest = PrayerRequest::where('status_id', 5)->where('number_of_notifications', '>', 0)->whereHas('conversation', function ($query) {
            $query->where('messages_id', 2)->where('status_conversation_id', 1)->whereNull('user_accepted');
        })->first();

        Log::info("Ativando a notificação.".json_encode($prayerRequest));

        if (!isset($prayerRequest)) {
            return;
        }

        $user = User::find($prayerRequest->user_id);

        $voluntaryController = new VoluntaryController($user);

        //setar os parametros;
        $paramns = [
            "id" => $prayerRequest->id,
            "datetime" => $prayerRequest->created_at,
            "number_notification" => $prayerRequest->number_of_notifications + 1
        ];
        
        $voluntaryController->resendMessageAllVoluntaries($prayerRequest->conversation->id, $paramns);

        Log::channel('notify_prayer_request')->info(json_encode($prayerRequest));
    }
}
