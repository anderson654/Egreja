@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Voluntarios'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Selecione o dia e hora que você pode atender a um pedido de oração.</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <form method="POST" action="{{ route('datetime.update', ['datetime' => $voluntary->id]) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12">
                                    <div class="p-4 pt-2">
                                        @foreach ($days as $day)
                                            <div class="form-check form-check-info text-start">
                                                {{-- <input class="form-check-input" type="checkbox" value="{{ $day->id }}"
                                                    name="days[]" checked> --}}
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    <a href="javascript:;"
                                                        class="text-dark font-weight-bolder">{{ $day->title }}</a>
                                                </label>
                                            </div>

                                            <div class="p-4 pt-2">
                                                <div class="d-flex" style="flex-wrap: wrap;">
                                                    @foreach ($hours as $hour)
                                                        @if ($day->id === $hour->daysofweeks_id)
                                                            <div class="form-check form-check-info text-start me-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="{{ $hour->id }}" name="hours[]"
                                                                    @if ($hour->active) checked @endif>
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    <a href="javascript:;"
                                                                        class="text-dark font-weight-bolder">{{ $hour->time->title }}</a>
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- <div class="p-4 pt-2">
                                                @foreach ($hours as $hour)
                                                    <div class="d-flex" style="flex-wrap: wrap;">
                                                        <div class="form-check form-check-info text-start me-2">
                                                                @if ($day->id === $hour->daysofweeks_id)
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="flexCheckDefault" checked>
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    <a href="javascript:;"
                                                                        class="text-dark font-weight-bolder">00:00</a>
                                                                </label>
                                                                @endif
                                                            </div>
                                                    </div>
                                                @endforeach
                                            </div> --}}
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex flex-row-reverse px-4">
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


@section('modals')
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-left">
                            <h3 class="font-weight-bolder text-info text-gradient">Escolha uma senha</h3>
                            <p class="mb-0">resete sua senha para sua segurança.</p>
                        </div>
                        <div class="card-body">
                            <form role="form text-left">
                                <label>Senha</label>
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Email" aria-label="Email"
                                        aria-describedby="email-addon">
                                </div>
                                <label>Confirme sua senha</label>
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Password" aria-label="Password"
                                        aria-describedby="password-addon">
                                </div>
                                <div class="text-center">
                                    <button type="button"
                                        class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Salvar</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-4 text-sm mx-auto">
                                Don't have an account?
                                <a href="javascript:;" class="text-info text-gradient font-weight-bold">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    {{-- <script>
        var slider = document.getElementById('sliderDouble');
        var label = document.getElementById('slider-label');

        noUiSlider.create(slider, {
            start: [0, 24],
            connect: true,
            range: {
                'min': 0,
                'max': 24
            },
            format: wNumb({
                decimals: 0
            })
        });

        slider.noUiSlider.on('update', function(values, handle) {
            label.innerHTML = values[0] + 'h - ' + values[1] + 'h';
        });
    </script> --}}
@endsection
