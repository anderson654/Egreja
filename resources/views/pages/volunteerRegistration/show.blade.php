@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Aprovar voluntário'])
    {{-- <div id="alert">
        @include('components.alert')
    </div> --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Aguardando aprovação</p>
                            <button class="btn btn-primary btn-sm ms-auto">Aprovar</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Dados de cadastro</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Nome</label>
                                    <input class="form-control" type="text"
                                        value="{{ $voluntary->name ?? old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Sobrenome</label>
                                    <input class="form-control" type="text"
                                        value="{{ $voluntary->surname ?? old('surname') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Idade</label>
                                    <input class="form-control" type="text" value="{{ $voluntary->age ?? old('age') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Sexo</label>
                                    <input class="form-control" type="text" value="{{ $voluntary->sex }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Estado civil</label>
                                    <input class="form-control" type="text" value="{{ $voluntary->marital_status }}">
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">contatos</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Telefone</label>
                                    <input class="form-control" type="text"
                                        value="{{$voluntary->phone}}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email</label>
                                    <input class="form-control" type="email"
                                        value="{{ $voluntary->email ?? old('email') }}">
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Igreja</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Igreja</label>
                                    <input class="form-control" type="text"
                                        value="{{$voluntary->igreja}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Tempo de converção</label>
                                    <input class="form-control" type="email"
                                        value="{{ $voluntary->time_convertion ?? old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Batizado?</label>
                                    <input class="form-control" type="email"
                                        value="{{ $voluntary->batizado ?? old('email') }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Tempo</label>
                                    <input class="form-control" type="email"
                                        value="{{ $voluntary->time ?? old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Já foi voluntario?</label>
                                    <input class="form-control" type="email"
                                        value="{{ $voluntary->alredy_voluntary ?? old('email') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- carrocel --}}
            <div class="col-lg-4">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            <div class="carousel-item h-100 active"
                                style="background-image: url('/img/fe3.png');
                background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                    </div>
                                    <p>Em cada oração que oferecemos, cumprimos o mandamento divino de amar e cuidar uns dos
                                        outros. Nossa missão é mais do que palavras, é a expressão do amor de Deus em ação.
                                        Continuemos a ser o canal da Sua graça, levando alívio e paz aos corações que buscam
                                        conforto.</p>
                                    <h5 class="text-white mb-1">"Amando uns aos outros com amor fraternal, na honra,
                                        preferindo-vos em honra uns aos outros." - Romanos 12:10</h5>
                                </div>
                            </div>
                            <div class="carousel-item h-100"
                                style="background-image: url('/img/fe4.png');
                background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">"Vocês são a luz do mundo. Não se pode esconder uma cidade
                                        construída sobre um monte." - Mateus 5:14 (NVI)</h5>
                                    <p>Juntos, somos a luz que ilumina os caminhos daqueles que precisam. Nossa dedicação e
                                        amor fazem a diferença a cada oração oferecida. Continuemos a espalhar esperança e
                                        compaixão, guiando aqueles que buscam conforto. Nossa missão é o coração pulsante
                                        deste projeto, e juntos, somos imparáveis.</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100"
                                style="background-image: url('/img/fe5.png');
                background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-trophy text-dark opacity-10"></i>
                                    </div>
                                    <p>Recebemos de Deus a missão mais nobre: orar uns pelos outros. Nas nossas preces,
                                        encontramos a força que une corações e transforma vidas. Como instrumentos de Sua
                                        graça, nossas orações são luz e esperança para todos. Continuemos a cumprir esse
                                        chamado divino com amor e fervor.</p>
                                    <h5 class="text-white mb-1">"Orai uns pelos outros, para serdes curados. A oração feita
                                        por um justo pode muito em seus efeitos." - Tiago 5:16 (NVI)</h5>

                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Proximo</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
