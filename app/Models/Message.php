<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->subHours(3)->format('d/m/Y H:i');
        return $dataFormat;
    }
}
