<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedRequest extends Model
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
}
