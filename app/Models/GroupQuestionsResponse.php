<?php

namespace App\Models;

use App\Models\WhatsApp\GroupsResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupQuestionsResponse extends Model
{
    use HasFactory;

    public function group_response()
    {
        return $this->hasOne(GroupsResponse::class, 'id', 'groups_responses_id');
    }
    public function responses_positive()
    {
        return $this->hasOne(GroupsResponse::class, 'id', 'groups_responses_id')->where('responses_role_id', 1);
    }
    public function responses_negative()
    {
        return $this->hasOne(GroupsResponse::class, 'id', 'groups_responses_id')->where('responses_role_id', 2);
    }
    public function responses_exit()
    {
        return $this->hasOne(GroupsResponse::class, 'id', 'groups_responses_id')->where('responses_role_id', 3);
    }
}
