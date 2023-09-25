<?php

namespace App\Models;

use App\Models\WhatsApp\GroupsResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsesToGroup extends Model
{
    use HasFactory;

    public function group_response(){
        return $this->hasOne(GroupsResponse::class,'id','group_responses_id');
    }
}
