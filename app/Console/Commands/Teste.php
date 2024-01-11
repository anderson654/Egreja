<?php

namespace App\Console\Commands;

use App\Console\Commands\Messages\NotifyPrayerRequest;
use App\Console\Commands\Vonluntary\CheckHelp;
use App\Models\PrayerRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Teste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:teste';

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

    }


    /**
     * Esta função remove todos os que não são prayer request.
     */
    public function removeNotIsPrayerRequest()
    {
        $all = PrayerRequest::get();

        //atendimeentos cancelado pelo user
        $prayerRequests = PrayerRequest::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 3)->where('status_conversation_id', 3);
        });
        $prayerRequests->delete();
        //atendimeentos cancelado por falta de resposta
        $prayerRequests = PrayerRequest::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 1)->where('status_conversation_id', 3);
        });
        $prayerRequests->delete();

    }
}
