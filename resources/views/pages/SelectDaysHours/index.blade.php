@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Horários dos voluntarios'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="p-4">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                @foreach ($dados as $key => $dado)
                                    <button class="nav-link @if ($key === 0) active @endif"
                                        id="nav-home-tab-{{ $key }}" data-bs-toggle="tab"
                                        data-bs-target="#nav-home-{{ $key }}" type="button" role="tab"
                                        aria-controls="nav-home-{{ $key }}"
                                        aria-selected="true">{{ $dado->title }}</button>
                                @endforeach
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            @foreach ($dados as $key => $dado)
                                <div class="tab-pane fade @if ($key === 0) show active @endif"
                                    id="nav-home-{{ $key }}" role="tabpanel"
                                    aria-labelledby="nav-home-tab-{{ $key }}">
                                    {{-- <h1>{{ $key }}</h1> --}}
                                    <canvas id="meuGrafico-{{ $key }}"
                                        data-dayofweekid={{ $dado->id }}></canvas>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets\js\plugins\chartjs.min.js') }}"></script>

    <script>
        $('#nav-tab > button').click(function() {
            // Aqui você pode adicionar o código que deseja executar quando um filho é clicado
            console.log('Clicado em:', $(this).attr('id'));
        });

        $('[data-dayofweekid]').each(function(index, element) {
            const dayId = $(element).data('dayofweekid');
            const elementId = $(element).attr('id');
            $.ajax({
                url: `/admin/voluntary-times?dayofweek=${dayId}`,
                method: 'GET',
                data: {
                    // Dados que você deseja enviar para a controller
                },
                success: function(response) {
                    // Manipular a resposta da controller, se necessário
                    createGrapic(response, elementId);
                }
            });
        });

        // Exemplo de uma solicitação AJAX usando jQuery

        function createGrapic(data, idCanvas) {

            // Opções do gráfico
            const opcoes = {
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max.apply(null, data.datasets[0].data) * 2
                    }
                },
                onClick: function(e, element) {
                    console.log(element);
                },
                onHover: function(e, elementos) {
                    // Mudar o estilo do cursor para 'pointer' quando o mouse passa sobre as barras
                    if (!!elementos.length) {
                        $('#' + idCanvas).css('cursor', 'pointer');
                    } else {
                        $('#' + idCanvas).css('cursor', '');
                    }
                }
            };

            // Renderizar o gráfico
            const ctx = document.getElementById(idCanvas).getContext('2d');
            const meuGrafico = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: opcoes
            });
        }
    </script>
@endsection
