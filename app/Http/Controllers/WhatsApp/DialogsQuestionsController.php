<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\DialogsQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DialogsQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $ultimateQuestion = DialogsQuestion::where('dialog_template_id', $request->dialog_template_id)->orderBy('priority', 'desc');
        $priority = 1;
        if ($ultimateQuestion->exists()) {
            $priority = (int)$ultimateQuestion->first()['priority'];
            $priority += 1;
        }

        $request->merge(["priority" => $priority]);
        $validator = Validator::make($request->all(), [
            'question' => 'required|max:255|min:2',
            'dialog_template_id' => 'required',
            'priority' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Erro ao salvar.')->withErrors($validator)->withInput();
        }

        DialogsQuestion::create($request->all());

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

    public function updateOrder(Request $request, $id)
    {
        foreach ($request->updates as $obj) {
            DialogsQuestion::where('id', $obj['id'])->update(['priority' => $obj['index']]);
        }
        return response()->json(['message' => 'dados atualizados.']);
    }
}
