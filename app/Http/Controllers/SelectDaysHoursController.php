<?php

namespace App\Http\Controllers;

use App\Models\Daysofweek;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;

class SelectDaysHoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $dayofweek = $request->query('dayofweek') ?? 1;

            $timeInDay = Time::with(['select_days_hours' => function ($query) use ($dayofweek) {
                $query->where('daysofweeks_id', $dayofweek)->where('active', 1);
            }])->get();

            $data = [];
            for ($i = 0; $i < $timeInDay->count(); $i++) {
                # code...
                array_push($data, $timeInDay[$i]->select_days_hours->count());
            }

            $dados = [
                "labels" => $timeInDay->pluck('title'),
                "datasets" => [
                    [
                        'label' => 'voluntários',
                        'backgroundColor' => 'rgb(0, 152, 235)',
                        'borderColor' => 'rgb(251, 197, 66)',
                        'data' => $data,
                    ]
                ]
            ];
            return response()->json($dados, 200);
        }


        $dados = Daysofweek::get();
        return view('pages.SelectDaysHours.index', compact('dados'));
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
    public function show(Request $request, string $id)
    {
        //vai receber o dia e o horario como parametro de filtro
        $time_start = $request->query('time_start') ?? 1;
        
        $users = User::whereHas('select_days_hours', function ($query) use ($id, $time_start) {
            $query->where('daysofweeks_id', $id)->whereIn('times_id', [$time_start])->where('active', 1);
        })->get();

        return view('pages.SelectDaysHours.show', compact('users'));
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
