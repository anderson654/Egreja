<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectDaysHour extends Model
{
    use HasFactory;


    protected $fillable = [
        'active'
    ];

    public function time()
    {
        return $this->hasOne(Time::class, 'id', 'times_id');
    }
}
