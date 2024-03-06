<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daysofweek extends Model
{
    use HasFactory;

    public function select_days_hours()
    {
        return $this->hasMany(SelectDaysHour::class, 'daysofweeks_id', 'id');
    }

    public function getHoursAttribute()
    {

        $dayOfWeek = $this;

        return Time::with(['select_days_hours' => function ($query) use ($dayOfWeek) {
            $query->where('daysofweeks_id', $dayOfWeek->id)->where('active', 1);
        }])->get();
        // dd($this);
        // dd('Hello');
        // return 'Hello';
    }
}
