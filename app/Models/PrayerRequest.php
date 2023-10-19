<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrayerRequest extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function getStatusIdAttribute()
    {
        $statusId = $this->attributes['status_id'];
        $statusRequest = RequestStatuse::where('id', $statusId)->first();
        if ($statusRequest) {
            return $statusRequest->title;
        }
        return $statusId;
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
}
