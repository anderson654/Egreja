<?php

namespace App\Console\Commands\Vonluntary;

use Illuminate\Console\Command;
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Log::info('Hello Word');
    }
}
