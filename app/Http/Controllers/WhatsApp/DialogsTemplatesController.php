<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\DialogsQuestion;
use App\Models\DialogsTemplate;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\WhatsApp\GroupsResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DialogsTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dialogsTemplates = DialogsTemplate::get();
        return view('pages.whatsApp.index', compact('dialogsTemplates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|min:2'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Erro ao salvar.')->withErrors($validator)->withInput();
        }

        DialogsTemplate::create($request->all());

        return back()->with('succes', 'Salvo com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $dialogsTemplate = DialogsTemplate::where('id', $id)->with('dialog_questions.group_questions_responses.group_response')->first();
        $groupResponses = GroupsResponse::get();
        return view('pages.whatsApp.edit', compact('dialogsTemplate', 'id', 'groupResponses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    //api
    public function sendTemplate(Request $request)
    {
        //pegar o user
        $user = User::find($request->user_id);
        //pega a questão que inicia o template
        $questionTemplate = DialogsQuestion::where('dialog_template_id', $request->template_id)->where('start', true)->first();


        //cria uma nova chamada
        $prayerRequest = new PrayerRequest();
        $prayerRequest = $prayerRequest->newPrayerRequest($user, $questionTemplate);

        //enviar mensagem para o user
        $zapiController = new ZApiController();

        $message = str_replace("{{REQUESTER_NAME}}", isset($prayerRequest->voluntary->username) ? $prayerRequest->voluntary->username : "UNDEFINED", $questionTemplate->question);
        $message = str_replace("{{VOLUNTEER_NAME}}", isset($prayerRequest->user->username) ? $prayerRequest->user->username : "UNDEFINED", $questionTemplate->question);

        $zapiController->sendMessage($user->phone, str_replace('\n', "\n", $message));

        return response()->json(["message" => "Success"]);
    }
}
