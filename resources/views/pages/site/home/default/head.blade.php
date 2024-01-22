<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $pageTitle ?? 'Título Padrão' }}</title>
    
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('head')
    @yield('css')
</head>
<body>
    @yield('content')

    <script src="{{ asset('assets/js/bootstrap/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap/bootstrap.min.js') }}"></script>
    @yield('js')
</body>
</html>