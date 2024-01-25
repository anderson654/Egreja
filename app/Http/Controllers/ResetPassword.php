<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Notifications\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Controller
{
    use Notifiable;

    public function show()
    {
        return view('auth.reset-password');
    }

    public function routeNotificationForMail()
    {
        return request()->email;
    }

    public function send(Request $request)
    {
        $email = $request->validate([
            'email' => ['required']
        ]);
        $user = User::where('email', $email)->first();

        if ($user) {
            $this->notify(new ForgotPassword($user->id));
            return back()->with('succes', 'An email was send to your email address');
        }
    }

    public function setPrimaryPassword(Request $request, string $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = User::find($id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }

        $usuario->password =$request->password;
        $usuario->first_access = Carbon::now();

        if(!$usuario->update()){
            return redirect()->back()->with('error', 'Erro ao salvar.');
        }

        return redirect()->back()->with('succes', 'Senha definida com sucesso.');
    }
}
