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
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <h5>Realize seu cadastro</h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register.perform') }}">
                                @csrf
                                <div class="flex flex-col mb-3">
                                    <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Name" value="{{ old('username') }}" >
                                    @error('username') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" value="{{ old('email') }}" >
                                    @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="password" name="password" class="form-control" placeholder="Senha" aria-label="Password">
                                    @error('password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="form-check form-check-info text-start">
                                    <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" >
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Eu concordo com o <a href="javascript:;" class="text-dark font-weight-bolder">Termos e
                                        condições de uso</a>
                                    </label>
                                    @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Cadastre-se</button>
                                </div>
                                <p class="text-sm mt-3 mb-0">Já tem cadastro? <a href="{{ route('login') }}"
                                        class="text-dark font-weight-bolder">Entrar</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
