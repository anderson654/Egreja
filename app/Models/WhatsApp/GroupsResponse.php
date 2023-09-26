<?php

namespace App\Models\WhatsApp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupsResponse extends Model
{
    use HasFactory;

    public function responses_role(){
        return $this->hasOne(ResponsesRole::class,'id','responses_role_id');
    }
}
