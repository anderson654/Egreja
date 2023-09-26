<?php

namespace App\Http\Controllers\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\ResponsesToGroup;
use App\Models\WhatsApp\GroupsResponse;
use Illuminate\Http\Request;

class GroupsResponsesController extends Controller
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
        $goupResponses = GroupsResponse::with('responses_role')->get();
        return view('pages.groupResponses.create', compact('goupResponses'));
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
        //vai enviar as repostas do grupo;
        $groupResponse = GroupsResponse::find($id);
        $responses = ResponsesToGroup::where('group_responses_id',$id)->get();
        return view('pages.groupResponses.edit', compact('groupResponse', 'responses'));
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
