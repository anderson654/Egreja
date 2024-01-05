@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => $title ?? 'edit user'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => $subtitle ?? 'Perfil'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            @if (!$voluntary->user_id)
                                <p class="mb-0">Perfil</p>
                                {{-- //verificar se já estiver aprovado e remover o btn --}}
                                {{-- <button class="btn btn-primary btn-sm ms-auto"
                                    data-update="{{ $voluntary->id }}">Aprovar</button> --}}
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('voluntary.update', ['voluntary' => $voluntary->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="text-uppercase text-sm">Dados de cadastro</h6>
                                <div class="dropdown">
                                    <button class="btn bg-gradient-info dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        Açôes
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="#"
                                                data-delete={{ $voluntary->id }}>Excluir</a></li>
                                        {{-- <li><a class="dropdown-item" href="#">Another action</a></li>
                                        <li><a class="dropdown-item" href="#">Something else here</a></li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nome</label>
                                        <input class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                                            type="text" value="{{ old('firstname') ?? $voluntary->name }}"
                                            name="firstname">
                                        @if ($errors->has('firstname'))
                                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Sobrenome</label>
                                        <input class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}"
                                            type="text" value="{{ old('lastname') ?? $voluntary->surname }}"
                                            name="lastname">
                                        @if ($errors->has('lastname'))
                                            <span class="text-danger">{{ $errors->first('lastname') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Idade</label>
                                        <input class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}"
                                            type="text" value="{{ old('age') ?? $voluntary->age }}" name="age">
                                        @if ($errors->has('age'))
                                            <span class="text-danger">{{ $errors->first('age') }}</span>
                                        @endif
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
                                        <input class="form-control" type="text"
                                            value="{{ $voluntary->marital_status }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">contatos</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefone</label>
                                        <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                            type="text" value="{{ old('phone') ?? $voluntary->phone }}" name="phone">
                                        @if ($errors->has('phone'))
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email</label>
                                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            type="email" value="{{ old('email') ?? $voluntary->email }}" name="email">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Igreja</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Igreja</label>
                                        <input class="form-control" type="text" value="{{ $voluntary->igreja }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Tempo de
                                            converção</label>
                                        <input class="form-control" type="text"
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
                                        <input class="form-control" type="text"
                                            value="{{ $voluntary->time ?? old('email') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Já foi
                                            voluntario?</label>
                                        <input class="form-control" type="email"
                                            value="{{ $voluntary->alredy_voluntary ?? old('email') }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12  mt-2">
                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn bg-gradient-success">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


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

@section('script')
    <script>
        $(document).ready(function() {
            //atribui a função
            $('[data-delete]').on('click', event => editUser(event))
        })

        function editUser(event) {
            const idUser = $(event.currentTarget).data('delete');

            $.ajax({
                url: `/admin/voluntary/${idUser}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Define o cabeçalho CSRF-Token
                },
                success: function(response) {
                    // Lida com a resposta do servidor, se necessário
                    // lineTable.fadeOut(500);
                    window.location.href = '/admin/voluntary';
                },
                error: function(error) {
                    // Lida com erros, se houver
                    console.error(error);
                },
                complete: function() {
                    // Esta função será executada independentemente de sucesso ou erro
                    // console.log('Solicitação concluída.');
                    // location.reload()
                }
            });
        }
    </script>
@endsection
