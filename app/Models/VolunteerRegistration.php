<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'age',
        'sex',
        'marital_status',
        'phone',
        'email',
        'igreja',
        'time',
        'time_convertion',
        'batizado',
        'alredy_voluntary'
    ];


    public function setPhoneAttribute($value)
    {
        $limpaString = preg_replace("/[^0-9]/", "", $value);
        $limpaString = str_replace(" ", "", $limpaString);
        $this->attributes['phone'] = $limpaString;
    }
}
