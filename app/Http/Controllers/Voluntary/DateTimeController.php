<?php

namespace App\Http\Controllers\Voluntary;

use App\Http\Controllers\Controller;
use App\Models\Daysofweek;
use App\Models\SelectDaysHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DateTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $days = Daysofweek::get();
        $hours = SelectDaysHour::with('time')->where('user_id', Auth::user()->id)->get();
        return view('pages.voluntary.datetime.index',compact('days','hours'));
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
