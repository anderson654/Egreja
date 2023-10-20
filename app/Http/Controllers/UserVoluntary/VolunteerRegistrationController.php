<?php

namespace App\Http\Controllers\UserVoluntary;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VolunteerRegistration;
use Illuminate\Http\Request;

class VolunteerRegistrationController extends Controller
{
    public function index()
    {
        //
        $voluntaryes = VolunteerRegistration::whereNull('is_aproved')->orWhere('is_aproved', 0)->get();
        return view('pages.volunteerRegistration.index', compact('voluntaryes'));
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
        //pega um voluntario
        $voluntary = VolunteerRegistration::find($id);
        return view('pages.volunteerRegistration.show', compact('voluntary'));
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
        // criar um novo user

        if($request->ajax()){
            $voluntary = VolunteerRegistration::find($id);
            $voluntary->is_aproved = 1;
            $voluntary->save();

            //salvar o usuario na tabela users
            $data = [
                "username" => $voluntary->name . " " .  $voluntary->surname,
                "email" => $voluntary->email,
                "phone" => $voluntary->phone,
                "is_active" => 1
            ];
            
            $user = User::create($data);
            return response()->json($user);
        }
        $voluntary = VolunteerRegistration::find($id)->update($request->all());
        if(!$voluntary){
            dd('Erro');
        }

        dd($voluntary);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
