@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Voluntarios'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="text" id="template-id" value="{{ $groupResponse->id }}" style="display: none">
    <div class="card shadow-lg mx-4 card-profile-bottom mt-5">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $groupResponse->title }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            Template de mensagens WhatsApp
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>Mensagens</h6>
                            <div class="d-flex">
                                <button type="button" class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalSignUp">
                                    <i class="ni ni-fat-add text-ligth text-sm opacity-10"></i>
                                    adicionar mensagem
                                </button>
                            </div>
                            @include('components.Modals.create-response-whats')
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-secondary opacity-7"></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Mensagem</th>
                                    </tr>
                                </thead>
                                <tbody id="table-options">
                                    @if ($responses->count() === 0)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6>Nenhum registro</h6>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($responses as $response)
                                        <tr data-questionid="{{ $response->id }}">
                                            <td class="move">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <span class="material-icons" style="cursor: pointer">
                                                            code
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $response->response }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ '' }}</p>
                                                    </div>
                                                </div>
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
