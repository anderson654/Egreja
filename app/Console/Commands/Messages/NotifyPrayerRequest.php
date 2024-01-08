<?php

namespace App\Console\Commands\Messages;

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
        //
        Log::info('Heello');
    }
}
