<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\DialogsTemplate;
use Illuminate\Http\Request;

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
        // dd($dialogsTemplate);
        return view('pages.whatsApp.edit', compact('dialogsTemplate'));
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
}
