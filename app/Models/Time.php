<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    public function select_days_hours()
    {
        return $this->hasMany(SelectDaysHour::class, 'times_id', 'id')->has('user');
    }
}
