<?php

namespace App\Console\Commands\Voluntary;

use App\Models\Daysofweek;
use App\Models\Notification;
use App\Models\SelectDaysHour;
use App\Models\Time;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-days';

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
        $notification = Notification::where('status_notifications_id', 1)->where('type_notifications_id', 5)->has('user')->with('user')->first();
        if($notification){
            $this->createDaysVoluntary($notification->user);
        }
    }


    public function createDaysVoluntary($voluntary)
    {
        $userId = $voluntary->id;
        $days = Daysofweek::get();
        $times = Time::get();

        $recordsToInsert = [];

        foreach ($days as $day) {
            foreach ($times as $time) {
                $dayExist = SelectDaysHour::where('user_id', $userId)
                    ->where('daysofweeks_id', $day->id)
                    ->where('times_id', $time->id)
                    ->exists();

                if (!$dayExist) {
                    $recordsToInsert[] = [
                        'user_id' => $userId,
                        'daysofweeks_id' => $day->id,
                        'times_id' => $time->id,
                        'active' => true,
                    ];
                }
            }
        }

        if (!empty($recordsToInsert)) {
            // Use a transação para garantir a consistência
            DB::beginTransaction();

            try {
                SelectDaysHour::insert($recordsToInsert);

                // Commit a transação se tudo der certo
                DB::commit();
            } catch (\Exception $e) {
                // Reverta a transação em caso de erro
                DB::rollback();
                // Lidar com o erro (registre, notifique, etc.)
                // throw $e; // Descomente esta linha se desejar propagar a exceção
            }
        }
    }
}
