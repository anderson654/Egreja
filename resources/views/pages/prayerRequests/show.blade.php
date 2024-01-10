@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detalhes pedido'])
    {{-- <div id="alert">
        @include('components.alert')
    </div> --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Detalhes</h6>
                    </div>
                    <br>
                    <div class="d-flex justify-content-between">
                        <div class="author align-items-center px-4">
                            <img src="https://demos.creative-tim.com/argon-dashboard-pro-bs4/assets/img/team-5.jpg"
                                alt="..." class="avatar shadow">
                            <div class="name ps-3">
                                <h6>User</h6>
                                <span>Mathew Glock</span>
                                <div class="stats">
                                    <small>Posted on 28 February</small>
                                </div>
                            </div>
                        </div>
                        <div class="author align-items-center px-4">
                            <img src="https://demos.creative-tim.com/argon-dashboard-pro-bs4/assets/img/team-5.jpg"
                                alt="..." class="avatar shadow">
                            <div class="name ps-3">
                                <h6>Voluntario</h6>
                                <span>Mathew Glock</span>
                                <div class="stats">
                                    <small>Posted on 28 February</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="p-4" style="min-height: 100vh">
                            <div class="row mx-md-1" style="height:80vh;border: 1px solid #f0ebe3">
                                <div class="col-3 p-0" style="height:80vh;">
                                    <div class="card-wats p-2">
                                        <div class="d-flex justify-content-between">
                                            <p style="color: black">Anderson</p>
                                            <div class="d-flex justify-content-end">
                                                <p class="fs-6">data</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-9 px-2 backgound-whats">
                                    {{-- card --}}
                                    @foreach ($prayerRequest->conversation->historical_conversation as $conversation_history)
                                        <div class="d-flex flex-row">
                                            <div class="d-flex message-whats shadow my-1 p-2"
                                                style="background: #d9fdd3;border-radius: 10px;max-width: 70%">
                                                <p class="m-0 pe-2">{!! str_replace('\n', '<br>', $conversation_history->message['message']) !!}</p>
                                                <div class="d-flex flex-column-reverse">
                                                    <p class="m-0 fs-6">
                                                        {{ $conversation_history->message->created_at }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row-reverse">
                                            <div class="d-flex message-whats shadow my-1 p-2"
                                                style="background: #fff;border-radius: 10px">
                                                <p class="m-0 pe-2">{{ $conversation_history->response }}</p>
                                                <div class="d-flex flex-column-reverse">
                                                    <p class="m-0 fs-6">
                                                        {{ $conversation_history->simple_date }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- #d9fdd3 --}}
                                    {{--  fim card --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@section('script')
@endsection
