<?php

namespace App\Http\Controllers\UserVoluntary;

use App\Http\Controllers\Controller;
use App\Models\VolunteerRegistration;
use Illuminate\Http\Request;

class VolunteerRegistrationController extends Controller
{
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
        return view('pages.userVoluntary.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $attributes = request()->validate([
            'name' => 'required|max:255|min:2',
            'surname' => 'required|max:255|min:2',
            'age' => 'required',
            'sex' => 'required',
            'marital_status' => 'required|nullable|in:solteiro,casado,divorciado,viuvo',
            'email' => 'required|email|max:255|unique:volunteer_registrations,email',
            'igreja' => 'required',
            'time_convertion' => 'required',
            'batizado' => 'required',
            'alredy_voluntary' => 'required',
            'time' => 'nullable',
            'phone' => 'required|string',
        ]);
        VolunteerRegistration::create($attributes);
        return view('pages.userVoluntary.congrations');
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
