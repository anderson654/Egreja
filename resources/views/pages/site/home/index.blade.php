@extends('pages.site.home.default.head', ['pageTitle' => 'Minha Página'])


@section('content')
    <section class="vh-100 container-fluid p-0 section-one"  style="overflow: unset;">
        <div class="container-fluid vh-100 d-flex justify-content-end">
            <div class="w-50 vh-100 d-flex justify-content-center align-items-center flex-column">
                <div>
                    <h1 class="text-uppercase custom-size-title-h1" style="font-weight: 900">A oração</h1>
                    <h2 class="text-uppercase fs-1" style="font-weight: 500">Que você precisa<br><span style="font-weight: 800">a um click de distância.</span></h2>
                </div>
            </div>
        </div>
    </section>

    <section class="vh-100">
        <x-images.image-container-one>

        </x-images.image-container-one>
    </section>
@endsection
