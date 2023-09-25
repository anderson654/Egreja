<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialogsQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'dialog_template_id',
        'priority'
    ];
    
    public function group_questions_responses(){
        return $this->hasMany(GroupQuestionsResponse::class,'dialog_question_id','id');
    }

    public function setQuestionAttribute($value)
    {
        $this->attributes['question'] = str_replace("\r\n", '\n', $value);
    }
    public function getQuestionAttribute()
    {
        $question = $this->attributes['question'];
        return str_replace('\n',"\r\n", $question);
    }
}
