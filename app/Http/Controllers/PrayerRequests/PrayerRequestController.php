<?php

namespace App\Http\Controllers\PrayerRequests;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use App\Models\PrayerStatuse;
use App\Models\User;
use Illuminate\Http\Request;

class PrayerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $prayerRequests = PrayerRequest::with('user')->with('status:id,title')->whereHas('conversation', function ($query) {
            $query->where('messages_id', 2);
        })->orderBy('id', 'desc')->get();

        return view('pages.prayerRequests.index', compact('prayerRequests'));
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
        $prayerRequest = PrayerRequest::with('conversation.historical_conversation.message')
        ->with('conversation.voluntary')
        ->with('conversation.user')
        ->with('reference_conversations.user')
        ->with('reference_conversations.historical_conversation')
        ->find($id);



        // dd($prayerRequest);
        return view('pages.prayerRequests.show', compact('prayerRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = (object)[
            'prayerRequests' => PrayerRequest::with('user')->find($id),
            'prayerStatus' => PrayerStatuse::get()
        ];

        return view('pages.prayerRequests.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        PrayerRequest::find($id)->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
