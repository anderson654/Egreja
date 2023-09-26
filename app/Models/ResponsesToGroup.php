<?php

namespace App\Models;

use App\Models\WhatsApp\GroupsResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsesToGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'response',
        'group_responses_id'
    ];

    public function group_response(){
        return $this->hasOne(GroupsResponse::class,'id','group_responses_id');
    }
}
