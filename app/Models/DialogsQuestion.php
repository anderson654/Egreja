<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialogsQuestion extends Model
{
    use HasFactory;
    
    public function group_questions_responses(){
        return $this->hasMany(GroupQuestionsResponse::class,'dialog_question_id','id');
    }
}
