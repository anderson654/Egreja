@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
            style="background-image: url('./img/fe7.png'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Seja muito bem vindo irmão!</h1>
                        <p class="text-lead text-white">Use este formulário para criar uma nova conta.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-md-12 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <h5>Realize seu cadastro</h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register.voluntary.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="flex flex-col mb-3">
                                            <label for="name" class="form-control-label">Nome</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Nome" aria-label="Name" value="{{ old('name') }}">
                                            @error('name')
                                                <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="flex flex-col mb-3">
                                            <label for="surname" class="form-control-label">Sobrenome</label>
                                            <input type="surname" id="surname" name="surname" class="form-control"
                                                placeholder="Sobrenome" aria-label="surname" value="{{ old('surname') }}">
                                            @error('surname')
                                                <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="age" class="form-control-label">Idade</label>
                                        <input type="text" id="age" name="age" class="form-control"
                                            placeholder="Idade" aria-label="idade" value="{{ old('age') }}">
                                        @error('age')
                                            <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="idade" class="form-control-label">Sexo</label>
                                        <div class="row ms-3">
                                            <div class="form-check col-6">
                                                <input type="radio" id="m" name="sex" class="form-check-input"
                                                    value="M" {{ old('sex') === 'M' ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="m">Masculino</label>
                                                @error('sex')
                                                    <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                                @enderror
                                            </div>
                                            <div class="form-check col-6">
                                                <input type="radio" id="f" name="sex" class="form-check-input"
                                                    value="F" {{ old('sex') === 'F' ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="f">Femenino</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="civilState" class="form-control-label">Estado civil</label>
                                        <select class="form-control" name="marital_status" id="civilState"
                                            placeholder="Estado civil">
                                            <option value="null" selected="">- Selecione um estado civil -</option>
                                            <option
                                                value="solteiro"{{ old('marital_status') === 'solteiro' ? 'selected' : '' }}>
                                                Solteiro</option>
                                            <option value="casado"
                                                {{ old('marital_status') === 'casado' ? 'selected' : '' }}>
                                                Casado</option>
                                            <option value="divorciado"
                                                {{ old('marital_status') === 'divorciado' ? 'selected' : '' }}>Divorciado
                                            </option>
                                        </select>
                                        @error('marital_status')
                                            <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="phone" class="form-control-label">Telefone</label>
                                        <input type="text" id="phone" name="phone" class="form-control phone_with_ddd"
                                            placeholder="Telefone" aria-label="Name" value="{{ old('phone') }}">
                                        @error('phone')
                                            <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="email" class="form-control-label">E-mail</label>
                                        <input type="text" id="email" name="email" class="form-control"
                                            placeholder="E-mail" aria-label="Name" value="{{ old('email') }}">
                                        @error('email')
                                            <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="institution" class="form-control-label">Igreja</label>
                                        <input type="text" id="institution" name="igreja" class="form-control"
                                            placeholder="Igreja" aria-label="Name" value="{{ old('igreja') }}">
                                        @error('igreja')
                                            <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="timeConverteition" class="form-control-label">Tempo de
                                            converção</label>
                                        <input type="text" id="timeConverteition" name="time_convertion"
                                            class="form-control" placeholder="Tempo de converção" aria-label="Name"
                                            value="{{ old('time_convertion') }}">
                                        @error('time_convertion')
                                            <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="idade" class="form-control-label">Batizado</label>
                                        <div class="row ms-3">
                                            <div class="form-check col-6">
                                                <input type="radio" id="byes" name="batizado"
                                                    class="form-check-input" value=1
                                                    {{ old('batizado') === '1' ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="byes">Sim</label>
                                                @error('batizado')
                                                    <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                                @enderror
                                            </div>
                                            <div class="form-check col-6">
                                                <input type="radio" id="bno" name="batizado"
                                                    class="form-check-input" value=0
                                                    {{ old('batizado') === '0' ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="bno">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="timeEnableDisable" style="display: none;" hidden>
                                            <label for="time" class="form-control-label">A quanto tempo é
                                                batizado</label>
                                            <input type="text" id="time" name="time" class="form-control"
                                                placeholder="Tempo de converção" aria-label="Name"
                                                value="{{ old('time') }}">
                                            @error('time')
                                                <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="flex flex-col mb-3 col-md-6">
                                        <label for="idade" class="form-control-label">Você já foi voluntário em alguma
                                            área na Igreja?</label>
                                        <div class="row ms-3">
                                            <div class="form-check col-6">
                                                <input type="radio" id="vyes" name="alredy_voluntary"
                                                    class="form-check-input" value=1 {{ old('alredy_voluntary') === '1' ? 'checked' : '' }}/>
                                                <label class="custom-control-label" for="vyes">Sim</label>
                                                @error('alredy_voluntary')
                                                    <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                                @enderror
                                            </div>
                                            <div class="form-check col-6">
                                                <input type="radio" id="vno" name="alredy_voluntary"
                                                    class="form-check-input" value=0 {{ old('alredy_voluntary') === '0' ? 'checked' : '' }}/>
                                                <label class="custom-control-label" for="vno">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- <div class="form-check form-check-info text-start col-md-6">
                                    <input class="form-check-input" type="checkbox" name="terms"
                                        id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Eu concordo com o <a href="javascript:;"
                                            class="text-dark font-weight-bolder">Termos e
                                            condições de uso</a>
                                    </label>
                                    @error('terms')
                                        <p class='text-danger text-xs'> {{ $message }} </p>
                                    @enderror
                                </div> --}}
                                <div class="text-center">
                                    <button type="submit"
                                        class="btn bg-gradient-dark w-100 my-4 mb-2">Cadastre-se</button>
                                </div>
                                {{-- <p class="text-sm mt-3 mb-0">Já tem cadastro? <a href="{{ route('login') }}"
                                        class="text-dark font-weight-bolder">Entrar</a></p> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $("[name='batizado']").click(function() {
                // Seleciona o radio button marcado
                var valorSelecionado = $("input[name='batizado']:checked").val();
                if (valorSelecionado == 1) {
                    $("#timeEnableDisable").removeAttr("hidden");
                    $("#timeEnableDisable").fadeIn();
                    // Remover o atributo "hidden" do campo de entrada
                    return;
                }
                if (valorSelecionado == 0) {
                    $("#timeEnableDisable").attr("hidden", "true");
                    $("#timeEnableDisable").fadeOut();
                }
            })
        });
    </script>
    @include('layouts.footers.guest.footer')
@endsection
