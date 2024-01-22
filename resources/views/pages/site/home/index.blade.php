@extends('pages.site.home.default.head', ['pageTitle' => 'Minha Página'])


@section('content')
    <section class="vh-100 container-fluid p-0 section-one" style="overflow: unset;">
        <div class="container-fluid vh-100 d-flex justify-content-end  px-4">
            <div class="vh-100 d-flex justify-content-center align-items-center flex-column custom-section-one">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('img/logos/weblogo.png') }}" alt="" width="300px">
                    </div>
                    <h1 class="text-uppercase custom-size-title-h1" style="font-weight: 900">A oração</h1>
                    <h2 class="text-uppercase fs-1 mb-4" style="font-weight: 500">Que você precisa<br><span
                            style="font-weight: 800">a um click de distância.</span></h2>
                    <x-buttons.default-button link="https://wa.me/554184003554"
                        title="PRECISA DE UMA ORAÇÃO AGORA?"></x-buttons.default-button>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container py-5 my-5">
            <div class="row">
                <div class="col-md-6 px-md-5">
                    <x-images.image-container-one path='/img/perssona1.jpg' height="450px"></x-images.image-container-one>
                </div>
                <div class="col-md-6 px-md-5">
                    <div class="w-100 h-100 d-flex flex-column justify-content-center">
                        <p class="p-0 m-0 title-blue mb-4">UM OLHAR DE ESPERANÇA EM MEIO AO CAOS
                        </p>
                        <p class="fs-5">Estamos aqui para promover o bem-estar emocional em tempos desafiadores, com uma
                            visão de
                            compaixão e solidariedade.<br><br>
                            Dias difíceis e inesperados acontecem, mas você não está só.
                        </p>
                        <div class="my-4">
                            <x-buttons.default-button link="https://wa.me/554184003554"></x-buttons.default-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid p-0 section-tree d-flex align-items-center" style="overflow: unset;min-height: 70vh">
        <div class="container-fluid d-flex py-5 my-5">
            <div class="col-md-6">

            </div>
            <div class="col-md-5 d-flex flex-column justify-content-center" style="color: #626262;">
                <p class="title-blue p-0 m-0 mb-4">e-greja</p>
                <p class="fs-5">Em um mundo cada vez mais acelerado, onde as relações frequentemente ficam em segundo
                    plano, o alarmante aumento no indice de suicídios tem chamado a atenção. Nos momentos difíceis, a
                    carência de ouvidos atentos e palavras de encorajamento torna-se evidente. Visando o bem-estar da
                    população, criamos o projeto e-greja. Uma iniciativa de oração online para aliviar a dor que muitos
                    cidadãos enfrentam ao nosso redor.</p>
                <div class="my-4">
                    <x-buttons.default-button link="https://wa.me/554184003554"
                        title="PRECISA DE UMA ORAÇÃO AGORA?"></x-buttons.default-button>
                </div>
            </div>
        </div>
    </section>
    <section class="container py-5 my-5">
        <div class="row">
            <div class="col-md-6 px-md-5">
                <x-images.image-container-one path="/img/perssona2.jpg" height="450px"></x-images.image-container-one>
            </div>
            <div class="col-md-6 px-md-5">
                <div class="w-100 h-100 d-flex flex-column justify-content-center">
                    <p class="title-blue p-0 m-0 mb-5">nos ajude com
                        este projeto
                    </p>
                    <p class="fs-5">Existe três maneiras de nos ajudar:</p>
                    <p style="font-weight: 800">1 - Orando por nós
                    </p>
                    <p style="font-weight: 800">2 - Sendo um parceiro de coração se tornando um voluntário
                    </p>
                    <p style="font-weight: 800">3 - Enviando uma doação
                    </p>
                    {{-- <div class="my-3 d-flex" style="flex-wrap: wrap;">
                        <x-buttons.values-one></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 50,00'"></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 30,00'"></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 10,00'"></x-buttons.values-one>
                    </div> --}}


                    <div class="my-4 d-flex" style="flex-wrap: wrap;">
                        {{-- <x-buttons.values-two :value="'R$ 10,00'"></x-buttons.values-two> --}}
                        <x-buttons.default-button link="https://egreja.online/register-voluntary"
                            title="seja um voluntário"></x-buttons.default-button>
                    </div>
                    <div class="my-4 d-flex align-items-center custom-qr-code">
                        <img src="{{ asset('img\pixcode.jpg') }}" alt="pixcode">
                        <p class="ms-4 text-uppercase title-blue fs-5">pix cnpj: <span
                                style="font-weight: 700">48.684.948/0001-84</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 px-5" style="background: #017dc3">
        <div class="row">
            <div class="col-md-6">
                <p class="title-blue" style="color: #fff"><span style="font-weight: 600">sua</span> generosidade<br> <span
                        style="font-weight: 600">faz a</span> diferença</p>
                <div style="height: 2px;width: 80%;background: #f7c237"></div>
                <p class="fs-4 mt-3 mb-0  text-uppercase" style="font-weight: 800;color: #fff">pix cnpj:<span
                        style="font-weight: 600"> 48.684.948/0001-84</span></p>
                <p class="fs-4 mb-0 text-uppercase" style="font-weight: 800;color: #fff">banco: 748 - banco cooperativo</p>
                <p class="fs-4 mb-0 text-uppercase" style="font-weight: 800;color: #fff">sicreed s.a. - bansicred</p>
                <p class="fs-4 mb-0 text-uppercase" style="font-weight: 800;color: #fff">agência:<span
                        style="font-weight: 600"> 0752</span></p>
                <p class="fs-4 mb-0 text-uppercase" style="font-weight: 800;color: #fff">conta: <span
                        style="font-weight: 600">18983-9</span></p>
                <p class="fs-4 mb-0 text-uppercase" style="font-weight: 800;color: #fff">Razão social: <span
                        style="font-weight: 600">cidade belive</span></p>
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-center w-100 mb-5">
                    <div class="justify-content-center">
                        <p class="kristi p-0 m-0 text-center" style="font-size: 6rem;color: #fff;letter-spacing: 1px;">
                            Siga-nos:
                        </p>
                        <a href="https://www.instagram.com/egreja.online?igsh=MTcxNWlnZXcybGp0NQ==" style="text-decoration: none;">
                            <div class="py-2 px-5" style="background: #fff;border-radius: 40px">
                                <p class="mb-0 fs-4 text-center" style="color: #017dc3;font-weight: 700;">
                                    <i class="fa-brands fa-instagram p-2" style="background: #f7c237;border-radius: 20px"></i>
                                    /@egreja.online
                                </p>
                            </div>
                        </a>
                        {{-- <div class="d-flex align-items-center justify-content-center">
                                <div style="width: 80%">
                                    <div class="row text-center" style="color: #fff;font-weight: 200">
                                        <div class="col-md-4">
                                            <x-icons.bottom-bar-icons :pathImage="'/img/icons/socialmedias/GMAIL.png'">
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate, vel adipisci
                                                    voluptatibus dolorem voluptates cum error nam.</p>
                                            </x-icons.bottom-bar-icons>
                                        </div>
                                        <div class="col-md-4">
                                            <x-icons.bottom-bar-icons :pathImage="'/img/icons/socialmedias/YOUTUBE.png'">
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate, vel adipisci
                                                    voluptatibus dolorem voluptates cum error nam.</p>
                                            </x-icons.bottom-bar-icons>
                                        </div>
                                        <div class="col-md-4">
                                            <x-icons.bottom-bar-icons :pathImage="'/img/icons/socialmedias/LOCALIZACAO.png'">
                                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate, vel adipisci
                                                    voluptatibus dolorem voluptates cum error nam.</p>
                                            </x-icons.bottom-bar-icons>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                    </div>
                </div>
                <div class="justify-content-center">
                    <div class="mb-4 d-flex justify-content-center">
                        <img src="{{ asset('img/logos/weblogo.png') }}" alt="" width="200px">
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
