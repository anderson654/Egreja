<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialogsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function dialog_questions(){
        return $this->hasMany(DialogsQuestion::class,'dialog_template_id','id');
    }
}
