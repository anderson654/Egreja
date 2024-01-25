<?php

namespace App\Console\Commands;

use App\Console\Commands\Messages\NotifyPrayerRequest;
use App\Console\Commands\Vonluntary\CheckHelp;
use App\Models\Daysofweek;
use App\Models\PrayerRequest;
use App\Models\SelectDaysHour;
use App\Models\Time;
use App\Models\User;
use App\Models\UsersTest;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $this->getVoluntariesNotAttending();
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

    public function checkIsNumberTest()
    {
        dd(Utils::getNumbersTest());
    }

    public function getUsers()
    {
        dd(User::find(7)->tester);
    }

    public function createDefaultDaysOfWeek()
    {

        $voluntaries = User::where('role_id', 3)->get();

        foreach ($voluntaries as $key => $voluntary) {
            Log::info("User" . $voluntary->id);
            # code...
            // dd(SelectDaysHour::count());
            $userId = $voluntary->id;
            $days = Daysofweek::get();
            $times = Time::get();

            foreach ($days as $key => $day) {
                # code...
                foreach ($times as $key => $time) {
                    # code...
                    // se já existir não cria
                    $dayExist = SelectDaysHour::where('user_id', $userId)->where('daysofweeks_id', $day->id)->where('times_id', $time->id)->exists();
                    if (!$dayExist) {
                        $selectDaysHour = new SelectDaysHour();
                        $selectDaysHour->user_id = $userId;
                        $selectDaysHour->daysofweeks_id = $day->id;
                        $selectDaysHour->times_id = $time->id;
                        $selectDaysHour->active = true;
                        $selectDaysHour->save();
                    }
                }
            }
        }

        dd('final');
    }


    public function getVoluntariesNotAttending()
    {
        $users = User::where('role_id', 3)
            ->whereDoesntHave('conversations', function ($query) {
                $query->whereIn('status_conversation_id', [1, 2]);
            })
            ->has('answer_this_time')
            ->whereNotNull('phone')
            ->get();
        return $users;
    }
}
