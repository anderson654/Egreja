<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/img/logo.png">
    <title>
        {{-- Argon Dashboard 2 by Creative Tim --}}
        {{ $title ?? 'Egreja' }}
    </title>
    <!-- jquery -->
    <script src="/assets/js/core/code.jquery.com_jquery-3.7.1.min.js"></script>
    <!--jquery mask-->
    <script src="/assets/js/core/jquery.mask.min.js"></script>
    <script src="/assets/js/myMasks/masks.js"></script>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ mix('/assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!--drag and drop-->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <!--choise-->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/base.min.css" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css" />

    <style>
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 20px;
            /* Preferred icon size */
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;

            /* Support for all WebKit browsers. */
            -webkit-font-smoothing: antialiased;
            /* Support for Safari and Chrome. */
            text-rendering: optimizeLegibility;

            /* Support for Firefox. */
            -moz-osx-font-smoothing: grayscale;

            /* Support for IE. */
            font-feature-settings: 'liga';
            color: #344767;
        }

        .dataTable-selector {
            border-color: #e9ecef;
            border-radius: 0.25rem;
            text-transform: none;
            word-wrap: normal;
            padding: 6px
        }
    </style>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<body class="{{ $class ?? '' }}">
    {{-- criar um component modal --}}

    <!-- Button trigger modal -->
    {{-- <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button> --}}
    <!-- Modal -->
    <div class="modal fade" id="modalLoadding" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document" style="justify-content: center">
            <div class="spinner-border" style="color: #fff" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    {{-- fim modal --}}






    @guest
        @yield('content')
    @endguest

    @auth
        @if (in_array(request()->route()->getName(),
                ['sign-in-static', 'sign-up-static', 'login', 'register', 'recover-password', 'rtl', 'virtual-reality']))
            @yield('content')
        @else
            @if (
                !in_array(request()->route()->getName(),
                    ['profile', 'profile-static']))
                <div class="min-height-300  position-absolute w-100"
                    style="background-image: url('/img/bakground.png'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-7"></span>
                </div>
            @elseif (in_array(request()->route()->getName(),
                    ['profile-static', 'profile']))
                <div class="position-absolute w-100 min-height-300 top-0"
                    style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
            @include('layouts.navbars.auth.sidenav')
            <main class="main-content border-radius-lg" style="position: relative">
                <div style="position: absolute;right: 0;width: 100%;z-index: 101;">
                    @include('components.alert')
                </div>
                @yield('content')
            </main>
            @include('components.fixed-plugin')
        @endif
    @endauth

    <!---->


    <!--   Core JS Files   -->
    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/assets/js/argon-dashboard.js"></script>
    @stack('js');
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        $(document).ready(function() {
            if ($('.alert')) {
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 1000);
            }
        });

        //quil editor de texto
        //ajustar para script da pagina
        if ($('#editor').length) {
            var quill = new Quill('#editor', {
                modules: {
                    toolbar: true
                },
                theme: 'snow' // Specify theme in configuration
            });

            quill.on('text-change', function(delta, oldDelta, source) {
                $('#exampleFormControlInput1').val(quill.getText());
                console.log($('#exampleFormControlInput1').val());
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js"></script>

    <script src="{{ asset('assets/js/plugins/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/customPlugins/custom-datatables.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('assets/js/plugins/nouislider.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/wNumb.min.js') }}"></script>
    @yield('script')
</body>

</html>
