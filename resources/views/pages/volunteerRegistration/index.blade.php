@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Aprovar voluntário'])
    {{-- <div id="alert">
        @include('components.alert')
    </div> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>Aguardando aprovação</h6>
                            {{-- <a href="http://127.0.0.1:8000/admin/voluntary/create">
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">
                                    <i class="ni ni-fat-add text-ligth text-sm opacity-10"></i>
                                    Adicionar voluntario
                                </button>
                            </a> --}}
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Usuário</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Data do cadastro</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            açoes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($voluntaryes as $voluntary)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="/img/team-2.jpg" class="avatar avatar-sm me-3"
                                                            alt="user1">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $voluntary->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $voluntary->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{ $voluntary->created_at }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="" data-update="{{ $voluntary->id }}"
                                                    class="text-secondary font-weight-bold text-xs text-uppercase"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    aprovar
                                                </a>
                                                |
                                                <a href="{{ route('register.voluntary.show', ['id' => $voluntary->id]) }}"
                                                    class="text-secondary font-weight-bold text-xs text-uppercase"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    visualizar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
        <script>
            $(document).ready(function() {
                const elements = $('[data-update]');
                elements.click(function(e) {
                    e.preventDefault();
                    const lineTable = $(this).closest('tr');
                    const idUser = $(this).attr('data-update');
                    $("#exampleModalToggle").modal("show");
                    $.ajax({
                        url: `aprove-voluntary-update/${idUser}`,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Define o cabeçalho CSRF-Token
                        },
                        success: function(response) {
                            // Lida com a resposta do servidor, se necessário
                            lineTable.fadeOut(500);
                        },
                        error: function(error) {
                            // Lida com erros, se houver
                            console.error(error);
                        },
                        complete: function() {
                            // Esta função será executada independentemente de sucesso ou erro
                            console.log('Solicitação concluída.');
                            $("#exampleModalToggle").modal("hide");

                            // Execute outras ações aqui
                        }
                    });
                })
            })
        </script>
    </div>
@endsection
