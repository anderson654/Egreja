<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Channels\UserController;
use App\Http\Controllers\Channels\VoluntaryController;
use App\Models\Conversation;
use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\GroupQuestionsResponse;
use App\Models\PrayerRequest;
use App\Models\ResponsesToGroup;
use App\Models\SideDishes;
use App\Models\User;
use App\Models\VolunteerRegistration;
use App\Models\VolunteerRequest;
use App\Models\WhatsApp\HistoricalConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZApiWebHookController extends Controller
{

    private $date;
    private $phone;
    private $user;

    public function __construct(Request $request)
    {
        Log::info('Passou aqui tenho certeza');
        $this->date = $request->all();
        $this->phone = $request->phone;
    }


    public function getStatusMessage(Request $request)
    {
        Log::info("-------WebhoockLaravel: mensagen recebida---------");
        //se não existir criar um usuario role id = 4;
        $user = User::createNewUserZapi($this->phone, $this->date);
        $this->user = $user;

        //cada tipo de user tem  um canal então verificar antes
        switch ($user->role_id) {
            case 3:
                # Voluntario
                $voluntaryChannelController = new VoluntaryController($this->user);
                $voluntaryChannelController->initChanelVoluntary($this->date);
                break;
            case 4:
                # User
                $userUserChannelController = new UserController($this->user);
                $userUserChannelController->initChanelUser($this->date);
                break;

            default:
                # code...
                break;
        }

        return;
    }
}
