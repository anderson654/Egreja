<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class PrayerRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function prayer()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->where('role_id', 4);
    }

    public static function getCountByMonth()
    {
        $results = DB::table('prayer_requests')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        return $results;
    }

    public function voluntary()
    {
        return $this->hasOne(User::class, 'id', 'voluntary_id');
    }

    /**
     * @param User  $user recebe user
     * @param DialogsQuestion $question recebe uma  questão
     * @param  int $reference recebe o id de uma questão opicional
     * @param  int $statusId estado inicial do prayer_requests
     * @return PrayerRequest
     */

    //cria uma linha de registro na tabela prayer_requests
    public static function newPrayerRequest($user, $message, $reference = null, $statusId = null)
    {
        $prayerRequest = new PrayerRequest();
        $prayerRequest->user_id = $user->id;
        $prayerRequest->status_id = $statusId ?? 1;
        $prayerRequest->current_dialog_question_id = $message->id;
        $prayerRequest->reference = $reference;
        $prayerRequest->save();

        return $prayerRequest;
    }

    public function getCreatedAtAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->subHours(3)->format('d/m/Y H:i');
        return $dataFormat;
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'id', 'reference');
    }

    public function status()
    {
        return $this->hasOne(PrayerStatuse::class, 'id', 'status_id');
    }

    public function reference_conversations()
    {
        return $this->hasMany(Conversation::class, 'reference_conversation_id', 'reference');
    }
}
