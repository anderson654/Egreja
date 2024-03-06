<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main" style="z-index: 100">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <div class="d-flex justify-content-center">
            <a class="navbar-brand m-0" href="{{ route('home') }}" target="_blank">
                <img src="/img/logo.png" class="navbar-brand-img h-100" alt="main_logo">
                {{-- <span class="ms-1 font-weight-bold">Egreja </span> --}}
            </a>
        </div>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @can('define-access-pastor')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Gestão</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('prayerRequests.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                support
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">Pedidos de ajuda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('acompanhamentos.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                groups
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">Acompanhamentos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('voluntary.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                badge
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">Voluntários</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('voluntary-times.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                schedule
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">Horários de atendimento</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('register.voluntary.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                how_to_reg
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">Aprovar voluntário</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Usuários</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('user.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                people
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">Usuário/Ajuda</span>
                    </a>
                </li>
            @endcan

            @can('define-access-admin')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Gestão de mensagens</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'tables') == true ? 'active' : '' }}"
                        href="{{ route('dialog-whatsapp.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <span class="material-icons">
                                chat
                            </span>
                        </div>
                        <span class="nav-link-text ms-1">WhatsApp</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Meus dados</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile-static' ? 'active' : '' }}"
                    href="{{ route('profile-static') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Perfil</span>
                </a>
            </li>
            @can('access-exclusive-voluntary')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'profile-static' ? 'active' : '' }}"
                        href="{{ route('datetime.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-time-alarm text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Quando posso atender</span>
                    </a>
                </li>
            @endcan

            @can('define-access-admin')
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('sign-in-static') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Entrar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('sign-up-static') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Cadastrar-se</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>

</aside>
