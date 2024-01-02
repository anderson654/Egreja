<?php

namespace App\Console\Commands;

use App\Console\Commands\Vonluntary\CheckHelp;
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
        //
        DB::beginTransaction();
        try {
            //code...
            $checkHelp = new CheckHelp();
            $checkHelp->sendPastorNotifications();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
            DB::rollBack();
        }
        // dd('Hello');
    }
}
