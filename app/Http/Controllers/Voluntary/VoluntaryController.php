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
        $users = VolunteerRegistration::has('user')->where('is_aproved', true)->get();
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
        return view('pages.volunteerRegistration.show', compact('voluntary', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $voluntary = VolunteerRegistration::find($id);
        $title = "Visualizar voluntario";
        $subtitle = "Informações sobre voluntario";
        return view('pages.volunteerRegistration.edit', compact('voluntary', 'title', 'subtitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'firstname' => 'required|min:3|max:255',
            'lastname' => 'required|min:3|max:255',
            'phone' => 'required',
            'email' => 'required|email|min:7|max:255',
            'age' => 'required|min:1|max:2'
        ], [
            'required' => 'O campo :attribute é requerido',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres'
        ]);

        $voluntaryRegistration = VolunteerRegistration::with('user')->find($id);
        $voluntaryRegistration->name = $request->firstname;
        $voluntaryRegistration->surname = $request->lastname;
        $voluntaryRegistration->phone = $request->phone;
        $voluntaryRegistration->email = $request->email;
        $voluntaryRegistration->age = $request->age;
        $voluntaryRegistration->save();

        // $voluntaryRegistration->sex = $request->sex;
        // $voluntaryRegistration->marital_status = $request->marital_status;
        // $voluntaryRegistration->igreja = $request->igreja;
        // $voluntaryRegistration->time = $request->time;
        // $voluntaryRegistration->time_convertion = $request->time_convertion;
        // $voluntaryRegistration->batizado = $request->batizado;
        // $voluntaryRegistration->alredy_voluntary = $request->alredy_voluntary;


        //update user
        $voluntaryRegistration->user->firstname = $request->firstname;
        $voluntaryRegistration->user->lastname = $request->lastname;
        $voluntaryRegistration->user->phone = $request->phone;
        $voluntaryRegistration->user->email = $request->email;
        $voluntaryRegistration->user->save();

        return redirect()->back()->with('success', 'Dados salvos com sucesso!');
        // $voluntaryRegistrations = VolunteerRegistration::where('user_id', $id)->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voluntaryRegistration = VolunteerRegistration::find($id);
        $voluntaryRegistration->user()->delete();

        if (!$voluntaryRegistration->delete()) {
            return response()->json(['Falha ao excluir user'], 422);
        }
        return response()->json(['success'], 200);
    }

    public function aprove()
    {
        //
    }
}
