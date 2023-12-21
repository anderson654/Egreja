@extends('pages.site.home.default.head', ['pageTitle' => 'Minha Página'])


@section('content')
    <section class="vh-100 container-fluid p-0 section-one" style="overflow: unset;">
        <div class="container-fluid vh-100 d-flex justify-content-end">
            <div class="w-50 vh-100 d-flex justify-content-center align-items-center flex-column">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('img/logos/weblogo.png') }}" alt="" width="300px">
                    </div>
                    <h1 class="text-uppercase custom-size-title-h1" style="font-weight: 900">A oração</h1>
                    <h2 class="text-uppercase fs-1 mb-4" style="font-weight: 500">Que você precisa<br><span
                            style="font-weight: 800">a um click de distância.</span></h2>
                    <x-buttons.default-button title="PRECISA DE UMA ORAÇÃO AGORA?"></x-buttons.default-button>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container py-5 my-5">
            <div class="row">
                <div class="col-md-6 px-md-5">
                    <x-images.image-container-one height="450px"></x-images.image-container-one>
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
                            <x-buttons.default-button></x-buttons.default-button>
                        </div>
                    </div>
                </div>
            </div>


            {{-- //segunda parte 2 section --}}
            {{-- <div class="d-flex flex-column align-items-center py-3">
                <p class="kristi p-0 m-0" style="font-size: 8rem;color: #626262;letter-spacing: 1px;">Lorem ipsum dolor sit
                </p>
            </div>

            <div class="row mb-md-5">
                <div class="col-md-4 px-md-3">
                    <x-images.image-container-one height="350px">
                        <p class="text-center fs-5 mt-5" style="font-weight: bold;color: #626262;line-height: 1.4;">Lorem
                            ipsum dolor sit amet consectetur adipisicing elit?</p>
                        <p class="text-center" style="color: #626262;line-height: 1.4;">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Distinctio ad rem eius libero ex delectus sit corporis
                            perferendis.</p>
                    </x-images.image-container-one>
                </div>
                <div class="col-md-4 px-md-3">
                    <x-images.image-container-one height="350px">
                        <p class="text-center fs-5 mt-5" style="font-weight: bold;color: #626262;line-height: 1.4;">Lorem
                            ipsum dolor sit amet consectetur adipisicing elit?</p>
                        <p class="text-center" style="color: #626262;line-height: 1.4;">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Distinctio ad rem eius libero ex delectus sit corporis
                            perferendis.</p>
                    </x-images.image-container-one>
                </div>
                <div class="col-md-4 px-md-3">
                    <x-images.image-container-one height="350px">
                        <p class="text-center fs-5 mt-5" style="font-weight: bold;color: #626262;line-height: 1.4;">Lorem
                            ipsum dolor sit amet consectetur adipisicing elit?</p>
                        <p class="text-center" style="color: #626262;line-height: 1.4;">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Distinctio ad rem eius libero ex delectus sit corporis
                            perferendis.</p>
                    </x-images.image-container-one>
                </div>
            </div>

            <div class="row mb-md-5">
                <div class="col-md-4 px-md-3">
                    <x-images.image-container-one height="350px">
                        <p class="text-center fs-5 mt-5" style="font-weight: bold;color: #626262;line-height: 1.4;">Lorem
                            ipsum dolor sit amet consectetur adipisicing elit?</p>
                        <p class="text-center" style="color: #626262;line-height: 1.4;">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Distinctio ad rem eius libero ex delectus sit corporis
                            perferendis.</p>
                    </x-images.image-container-one>
                </div>
                <div class="col-md-4 px-md-3">
                    <x-images.image-container-one height="350px">
                        <p class="text-center fs-5 mt-5" style="font-weight: bold;color: #626262;line-height: 1.4;">Lorem
                            ipsum dolor sit amet consectetur adipisicing elit?</p>
                        <p class="text-center" style="color: #626262;line-height: 1.4;">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Distinctio ad rem eius libero ex delectus sit corporis
                            perferendis.</p>
                    </x-images.image-container-one>
                </div>
                <div class="col-md-4 px-md-3">
                    <x-images.image-container-one height="350px">
                        <p class="text-center fs-5 mt-5" style="font-weight: bold;color: #626262;line-height: 1.4;">Lorem
                            ipsum dolor sit amet consectetur adipisicing elit?</p>
                        <p class="text-center" style="color: #626262;line-height: 1.4;">Lorem ipsum dolor, sit amet
                            consectetur adipisicing elit. Distinctio ad rem eius libero ex delectus sit corporis
                            perferendis.</p>
                    </x-images.image-container-one>
                </div>
            </div> --}}
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
                    <x-buttons.default-button title="PRECISA DE UMA ORAÇÃO AGORA?"></x-buttons.default-button>
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
                    <p style="font-weight: 800">1 - Orar por nós
                    </p>
                    <p style="font-weight: 800">2 - Sendo um parceiro de coração se tornando um voluntário
                    </p>
                    <p style="font-weight: 800">3 - Enviando uma oração
                    </p>
                    <div class="my-3 d-flex" style="flex-wrap: wrap;">
                        <x-buttons.values-one></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 50,00'"></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 30,00'"></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 10,00'"></x-buttons.values-one>
                    </div>


                    <div class="my-4 d-flex" style="flex-wrap: wrap;">
                        <x-buttons.values-two :value="'R$ 10,00'"></x-buttons.values-two>
                        <x-buttons.default-button title="seja um voluntário"></x-buttons.default-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5" style="background: #017dc3">
        <p class="kristi p-0 m-0 text-center" style="font-size: 6rem;color: #fff;letter-spacing: 1px;">Siga-nos nas redes:
        </p>
        <div class="d-flex align-items-center justify-content-center">
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
        </div>
        <div class="mb-4 d-flex justify-content-center">
            <img src="{{ asset('img/logos/weblogo.png') }}" alt="" width="300px">
        </div>
    </section>
@endsection
