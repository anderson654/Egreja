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
        $voluntary = Auth::user();
        return view('pages.voluntary.datetime.index', compact('days', 'hours', 'voluntary'));
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
        $days = Daysofweek::get();
        $hours = SelectDaysHour::with('time')->where('user_id', $id)->get();
        $voluntary = Auth::user();
        return view('pages.voluntary.datetime.index', compact('days', 'hours', 'voluntary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hours = $request->all()['hours'];
        SelectDaysHour::where('user_id', $id)->update(['active' => false]);
        SelectDaysHour::whereIn('id', $hours)->where('user_id', $id)->update(['active' => true]);
        return back()->with('success', 'Recurso atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
