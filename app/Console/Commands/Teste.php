<?php

namespace App\Console\Commands;

use App\Console\Commands\Vonluntary\CheckHelp;
use Illuminate\Console\Command;

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
        //
        $checkHelp = new CheckHelp();
        $checkHelp->sendQuestionaryVoluntary();
        dd('Hello');
    }
}
