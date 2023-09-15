<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::where('role_id', 4)->get();
        return view('pages.user.index',compact('users'));
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

        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'firstname' => 'required|max:255|min:2',
            'lastname' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email'
        ]);

        $newUser = new User();
        $newUser->phone =  rand(1, 1000000000);
        $newUser->username = $attributes['username'];
        $newUser->firstname = $attributes['firstname'];
        $newUser->lastname = $attributes['lastname'];
        $newUser->email = 'e_greja_' . rand(1, 1000000000) . '@gmail.com';
        $newUser->password = '123456789';
        $newUser->role_id = 3;
        if (!$newUser->save()) {
            return back()->with('error', 'Erro ao criar usuario verifique com a TI');
        }
        return redirect()->route('voluntary.index')->with('succes', 'Voluntario salvo com sucesso.');
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
