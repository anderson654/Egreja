<?php

namespace App\Console\Commands\Messages;

use App\Models\Conversation;
use App\Models\PrayerRequest;
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
        $prayerRequest = PrayerRequest::where('status_id', 5)->whereHas('conversation', function ($query) {
            $query->where('message_id', 2)->where('status_conversation_id', 1)->whereNull('user_accepted');
        })->first();

        Log::channel('notify_prayer_request')->info(json_encode($prayerRequest));
    }
}
