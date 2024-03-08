@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Editar pedido de ajuda'])
    {{-- <div id="alert">
        @include('components.alert')
    </div> --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Editar pedido de ajuda</h6>
                    </div>
                    <div class="card-body p-4 pt-0 pb-2">
                        <div class="col-md-6">
                            <h5>Nome: {{$data->prayerRequests->user->username ?? 'anonimo'}}</h5>
                        </div>
                        <form action="{{ route('prayerRequests.update', ['prayerRequest' => $data->prayerRequests->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6">
                                <label for="status_id" class="form-control-label">Status do pedido</label>
                                <select class="form-control" name="status_id" id="status_id"
                                    placeholder="Status da chamada">
                                    <option value="null" selected="">- Status da chamada -</option>
                                    @foreach ($data->prayerStatus as $item)
                                        <option value="{{ $item->id }}" @if ($item->id === $data->prayerRequests->status_id) selected @endif>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-row-reverse">
                                <button type="submit" class="btn bg-gradient-success">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@section('script')
@endsection
