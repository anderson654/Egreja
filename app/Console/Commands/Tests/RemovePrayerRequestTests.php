<?php

namespace App\Console\Commands\Tests;

use App\Models\PrayerRequest;
use App\Models\UsersTest;
use App\Utils\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemovePrayerRequestTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-prayer-request-tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove todos os testes da prayer request';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('esta executando asdkljsadkasdaskjdh');
        $usersId = UsersTest::get()->pluck('user_id')->toArray();
        PrayerRequest::whereIn('user_id', $usersId)->delete();
    }
}
