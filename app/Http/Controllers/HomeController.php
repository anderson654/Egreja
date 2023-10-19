<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PrayerRequest;
use App\Models\VolunteerRegistration;
use Carbon\Carbon;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Instância do Carbon com a data atual
        $currentDate = Carbon::now();
        // mês atual no formato "xx" (com zero à esquerda, se necessário)
        $currentMonth = $currentDate->format('m');

        // Defina o dia para o primeiro dia do mês
        $firstDayOfMonth = $currentDate->firstOfMonth();

        $users = User::all();

        // Obtém o primeiro dia do mês anterior
        $firstDayOfPreviousMonth = $currentDate->subMonthNoOverflow()->firstOfMonth();

        // Suponhamos que $users seja uma coleção de registros da tabela 'users'

        // Conta o total de registros do mês atual
        $increase_registrations = $users->where('created_at', '>=', $firstDayOfMonth)->count();

        // Conta o total de registros do mês anterior
        $previous_month_registrations = $users->whereBetween('created_at', [$firstDayOfPreviousMonth, $firstDayOfMonth->subDay()])->count();

        // Calcula o percentual de crescimento
        $percentual_growth = ($increase_registrations - $previous_month_registrations) / ($previous_month_registrations ?: 1) * 100;

         // Instância do Carbon com a data atual
         $currentDate = Carbon::now();
         // mês atual no formato "xx" (com zero à esquerda, se necessário)
         $currentMonth = $currentDate->format('m');

         // Defina o dia para o primeiro dia do mês
         $firstDayOfMonth = $currentDate->firstOfMonth();

         $prayer_requests = PrayerRequest::all();

        // Filtra os pedidos de ajuda do mês atual
        $prayer_requests_current_month = $prayer_requests->filter(function ($prayer_request) use ($firstDayOfMonth) {
            return $prayer_request->created_at >= $firstDayOfMonth;
        });

        // Filtra os pedidos de ajuda do mês anterior
        $prayer_requests_previous_month = $prayer_requests->filter(function ($prayer_request) use ($firstDayOfPreviousMonth, $firstDayOfMonth) {
            return $prayer_request->created_at >= $firstDayOfPreviousMonth && $prayer_request->created_at < $firstDayOfMonth;
        });

        // Calcula o percentual de aumento
        $prayer_requests_current_month_count = $prayer_requests_current_month->count();
        $prayer_requests_previous_month_count = $prayer_requests_previous_month->count();

        // O resultado estará em $percentual_increase
        $percentual_increase = $prayer_requests_previous_month_count != 0
            ? round((($prayer_requests_current_month_count - $prayer_requests_previous_month_count) / $prayer_requests_previous_month_count) * 100, 2)
            : 100;


         // Instância do Carbon com a data atual
         $currentDate = Carbon::now();
         // mês atual no formato "xx" (com zero à esquerda, se necessário)
         $currentMonth = Carbon::now()->format('m');

         // Defina o dia para o primeiro dia do mês
         $firstDayOfMonth = Carbon::now()->firstOfMonth();

        $firstDayOfPreviousMonth = Carbon::now()->subMonthNoOverflow()->firstOfMonth();

        $volunteers_aproved = VolunteerRegistration::where('is_aproved', 1)->get();

        $volunteers = VolunteerRegistration::all();
        // Filtra os cadastros de voluntários aprovados do mês atual

        // Filtra os cadastros de voluntários aprovados do mês anterior

        // Calcula o percentual de aumento
        $volunteers_current_month_count = VolunteerRegistration::where('created_at', '>=', $firstDayOfMonth)->count();
        $volunteers_previous_month_count = VolunteerRegistration::where('created_at', '>=', $firstDayOfPreviousMonth)->where('created_at', '<', $firstDayOfMonth)->count();

        $volunteers_percentual_increase = $volunteers_previous_month_count != 0
        ? round((($volunteers_current_month_count - $volunteers_previous_month_count) / $volunteers_previous_month_count) * 100, 2)
        : 100;

        // O resultado estará em $percentual_increase
       $countsByMonth = PrayerRequest::getCountByMonth();
        // Combine os nomes dos meses com os valores dos dados
        // Crie um array vazio para armazenar os dados de exemplo
        foreach ($countsByMonth as $result) {

            $data[] = $result->count;
            $meses[] =  Carbon::createFromFormat('m', $result->month)->format('M');

        }


        $dataForChart = [
            'labels' => $meses,
            'datasets' => [
                [
                    'label' => 'Orações realizadas',
                    'tension' => 0.4,
                    'borderWidth' => 0,
                    'pointRadius' => 0,
                    'borderColor' => '#fb6340',
                    'backgroundColor' => 'gradientStroke1',
                    'borderWidth' => 3,
                    'fill' => true,
                    'data' => $data, // Seus dados reais aqui
                    'maxBarThickness' => 6
                ],
            ],
        ];

        $dataForChartJson = json_encode($dataForChart);

        return view('pages.dashboard', compact('users', 'prayer_requests', 'percentual_growth', 'percentual_increase', 'volunteers', 'volunteers_percentual_increase', 'volunteers_aproved', 'dataForChartJson'));
    }
}
