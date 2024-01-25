@auth
    <form method="POST" action="{{ route('password.update', ['id' => Auth::user()->id]) }}">
        @csrf
        @method('PUT')

        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
            data-bs-backdrop='static' data-bs-keyboard='false'>
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
                                        <input type="text" class="form-control" placeholder="senha" aria-label="Email"
                                            aria-describedby="email-addon" name="password">
                                    </div>
                                    @error('password')
                                        <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                    @enderror
                                    <label>Confirme sua senha</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Repita a senha"
                                            aria-label="Password" aria-describedby="password-addon"
                                            name="password_confirmation">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit"
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
    </form>
@endauth
