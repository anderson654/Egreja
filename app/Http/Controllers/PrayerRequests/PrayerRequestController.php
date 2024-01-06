<?php

namespace App\Http\Controllers\PrayerRequests;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
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
        $prayerRequests = PrayerRequest::with('user')->orderBy('id','desc')->get();

        return view('pages.prayerRequests.index',compact('prayerRequests'));
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
