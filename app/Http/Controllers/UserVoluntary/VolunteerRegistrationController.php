<?php

namespace App\Http\Controllers\UserVoluntary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ZApiController;
use App\Models\Daysofweek;
use App\Models\Notification;
use App\Models\SelectDaysHour;
use App\Models\Time;
use App\Models\User;
use App\Models\VolunteerRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($request->ajax()) {
            $voluntary = VolunteerRegistration::find($id);
            $voluntary->is_aproved = 1;
            $voluntary->save();

            //salvar o usuario na tabela users
            $data = [
                "username" => $voluntary->name . " " .  $voluntary->surname,
                "email" => $voluntary->email,
                "password" => '123456789',
                "phone" => "55" . $voluntary->getRawOriginal('phone'),
                "role_id" => 3,
                "is_active" => 1
            ];

            $user = User::create($data);
            //salvar o id que foi gerado do user no voluntario
            $voluntary->user_id = $user->id;
            $voluntary->save();

            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->type_notifications_id = 5;
            $notification->status_notifications_id = 1;
            $notification->save();

            //enviar link de cadastro
            $zApiController = new ZApiController();
            $zApiController->sendMessage($user->getRawOriginal('phone'), "Você foi aprovado como voluntario, para configurar seus horarios de atendimento acesse:\nhttps://egreja.online/admin/datetime\nEmail: ".$user->email."\nSenha: 123456789");

            return response()->json($user);
        }
        $voluntary = VolunteerRegistration::find($id)->update($request->all());
        if (!$voluntary) {
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

    public function createDaysVoluntary($voluntary)
    {
        $userId = $voluntary->id;
        $days = Daysofweek::get();
        $times = Time::get();

        $recordsToInsert = [];

        foreach ($days as $day) {
            foreach ($times as $time) {
                $dayExist = SelectDaysHour::where('user_id', $userId)
                    ->where('daysofweeks_id', $day->id)
                    ->where('times_id', $time->id)
                    ->exists();

                if (!$dayExist) {
                    $recordsToInsert[] = [
                        'user_id' => $userId,
                        'daysofweeks_id' => $day->id,
                        'times_id' => $time->id,
                        'active' => true,
                    ];
                }
            }
        }

        if (!empty($recordsToInsert)) {
            // Use a transação para garantir a consistência
            DB::beginTransaction();

            try {
                SelectDaysHour::insert($recordsToInsert);

                // Commit a transação se tudo der certo
                DB::commit();
            } catch (\Exception $e) {
                // Reverta a transação em caso de erro
                DB::rollback();
                // Lidar com o erro (registre, notifique, etc.)
                // throw $e; // Descomente esta linha se desejar propagar a exceção
            }
        }
    }
}
