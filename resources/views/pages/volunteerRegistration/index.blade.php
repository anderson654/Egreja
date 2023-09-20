@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Aprovar voluntário'])
    {{-- <div id="alert">
        @include('components.alert')
    </div> --}}
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
                                                        <h6 class="mb-0 text-sm">{{$voluntary->name}}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{$voluntary->email}}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{$voluntary->created_at}}</p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;"
                                                    class="text-secondary font-weight-bold text-xs text-uppercase"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    aprovar
                                                </a>
                                                |
                                                <a href="{{route('register.voluntary.show',['id' => $voluntary->id])}}"
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
    </div>
@endsection
