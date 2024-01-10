<?php

namespace App\Console\Commands\PrayerRequest;

use App\Models\PrayerRequest;
use Illuminate\Console\Command;

class UpdateStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //atendimento realizado com sucesso
        $prayerRequests = PrayerRequest::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 2)->has('voluntary');
        });
        $prayerRequests->update(['status_id' => 3]);

        //atendimeentos cancelado por falta de resposta
        $prayerRequests = PrayerRequest::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 1)->where('status_conversation_id', 3);
        });
        $prayerRequests->update(['status_id' => 8]);

        //falha no atendimento
        $prayerRequests = PrayerRequest::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 2)->where('status_conversation_id', 3)->whereNull('user_accepted');
        });
        $prayerRequests->update(['status_id' => 9]);

        //atendimeentos cancelado pelo user
        $prayerRequests = PrayerRequest::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 3)->where('status_conversation_id', 3);
        });
        $prayerRequests->update(['status_id' => 7]);
    }
}
