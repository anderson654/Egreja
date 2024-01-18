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
                        <br>
                        <p>Status: {{ $prayerRequest->status->title }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="author align-items-center px-4">
                            <img src="/img/team-2.jpg" alt="..." class="avatar shadow">
                            <div class="name ps-3">
                                <span>{{ $prayerRequest->conversation->user->username }}</span>
                                <div class="stats">
                                    <small>solicitou pedido de ajuda</small>
                                </div>
                            </div>
                        </div>
                        <div class="author align-items-center px-4">
                            <img src="/img/team-2.jpg" alt="..." class="avatar shadow">
                            <div class="name ps-3">
                                <span>{{ $prayerRequest->conversation->voluntary->username ?? 'Vazio' }}</span>
                                <div class="stats">
                                    <small>Aceitou pedido de ajuda</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="horizontal dark">
                    <br>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="p-4" style="min-height: 100vh">
                            <div class="row mx-md-1" style="height:80vh;border: 1px solid #f0ebe3">
                                <div class="col-3 p-0" style="height:80vh;overflow: auto">
                                    {{-- //aqui vai ter uma  classe de click e um for; --}}
                                    <div class="card-wats p-2">
                                        <p class="fs-7 m-0 ps-1">Solicitou ajuda</p>
                                    </div>
                                    <div class="card-wats p-2" data-conversation={{ $prayerRequest->conversation->id }}>
                                        <div class="d-flex justify-content-between">
                                            <p style="color: black">{{ $prayerRequest->conversation->user->username }}</p>
                                            <div class="d-flex justify-content-end">
                                                <p class="fs-6">data</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if (isset($prayerRequest->conversation->voluntary->id))
                                        <div class="card-wats p-2">
                                            <p class="fs-7 m-0 ps-1">Aceitou pedido</p>
                                        </div>
                                        @foreach ($prayerRequest->reference_conversations as $reference_conversatio)
                                            @if ($reference_conversatio->user->id == $prayerRequest->conversation->voluntary->id)
                                                <div class="card-wats p-2"
                                                    data-conversation={{ $reference_conversatio->id }}>
                                                    <div class="d-flex justify-content-between">
                                                        <p style="color: black">{{ $reference_conversatio->user->username ?? 'N/A'}}
                                                        </p>
                                                        <div class="d-flex justify-content-end">
                                                            <p class="fs-6">data</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    <div class="card-wats p-2">
                                        <p class="fs-7 m-0 ps-1">Pedidos enviados</p>
                                    </div>
                                    @foreach ($prayerRequest->reference_conversations as $reference_conversatio)
                                        @if (isset($prayerRequest->conversation->voluntary->id))
                                            @if ($reference_conversatio->user->id != $prayerRequest->conversation->voluntary->id)
                                                <div class="card-wats p-2"
                                                    data-conversation={{ $reference_conversatio->id }}>
                                                    <div class="d-flex justify-content-between">
                                                        <p style="color: black">{{ $reference_conversatio->user->username ?? 'N/A'}}
                                                        </p>
                                                        <div class="d-flex justify-content-end">
                                                            <p class="fs-6">data</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="card-wats p-2" data-conversation={{ $reference_conversatio->id }}>
                                                <div class="d-flex justify-content-between">
                                                    <p style="color: black">{{ $reference_conversatio->user->username ?? 'N/A'}}
                                                    </p>
                                                    <div class="d-flex justify-content-end">
                                                        <p class="fs-6">data</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- //converças --}}
                                <div class="col-9 px-2 backgound-whats">
                                    <div data-history={{ $prayerRequest->conversation->id }}>
                                        {{-- card --}}
                                        <div class="d-flex justify-content-center">
                                            <div class="d-flex message-whats shadow my-1 p-2"
                                                style="background: #fff;border-radius: 10px">
                                                <p class="m-0 pe-2">{{ $prayerRequest->conversation->created_at }}</p>
                                            </div>
                                        </div>

                                        @foreach ($prayerRequest->conversation->historical_conversation as $conversation_history)
                                            <div
                                                class="d-flex flex-row {{ $conversation_history->is_boot ? 'flex-row-reverse' : '' }}">
                                                <div class="d-flex message-whats shadow my-1 p-2"
                                                    style="background: {{ $conversation_history->is_boot ? '#d9fdd3' : '#fff' }};border-radius: 10px;max-width: 70%">
                                                    <p class="m-0 pe-2">{!! str_replace('\n', '<br>', $conversation_history->response) !!}</p>
                                                    <div class="d-flex flex-column-reverse">
                                                        <p class="m-0 fs-7">
                                                            {{ $conversation_history->created_at }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        {{-- #d9fdd3 --}}
                                        {{--  fim card --}}
                                    </div>

                                    @foreach ($prayerRequest->reference_conversations as $reference_conversatio)
                                        <div data-history={{ $reference_conversatio->id }} style="display:none;">
                                            @foreach ($reference_conversatio->historical_conversation as $conversation_history)
                                                <div
                                                    class="d-flex flex-row {{ $conversation_history->is_boot ? 'flex-row-reverse' : '' }}">
                                                    <div class="d-flex message-whats shadow my-1 p-2"
                                                        style="background: {{ $conversation_history->is_boot ? '#d9fdd3' : '#fff' }};border-radius: 10px;max-width: 70%">
                                                        <p class="m-0 pe-2">{!! str_replace('\n', '<br>', $conversation_history->response) !!}</p>
                                                        <div class="d-flex flex-column-reverse">
                                                            <p class="m-0 fs-7">
                                                                {{ $conversation_history->created_at }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
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
    <script>
        $('[data-conversation]').on('click', function() {
            $('[data-history]').fadeOut();
            $(`[data-history=${$(this).data('conversation')}]`).fadeIn();
        });
    </script>
@endsection
