<?php

namespace App\Http\Controllers\Voluntary;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VolunteerRegistration;
use Illuminate\Http\Request;

class VoluntaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = VolunteerRegistration::where('is_aproved', true)->get();
        // $users = User::where('role_id', 3)->get();
        return view('pages.voluntary.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.voluntary.register');
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
        //aqui a view
        $voluntary = VolunteerRegistration::find($id);
        $title = "Visualizar voluntario";
        $subtitle = "Informações sobre voluntario";
        return view('pages.volunteerRegistration.show', compact('voluntary','title','subtitle'));
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

    public function aprove()
    {
        //
    }
}
