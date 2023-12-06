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
                    <x-buttons.default-button></x-buttons.default-button>
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
                        <p class="kristi p-0 m-0" style="font-size: 6rem;color: #626262;letter-spacing: 1px;">Lorem Ipsom
                        </p>
                        <p>Lorem ipsum dolors
                            itamet,con
                            sectetur adipis cin gelit, sed doeius modte mpo rinci didun tut
                            lab ore etdo lorem ag naal iqua.Quisipsum
                            suspe ndis seultri cesgr avi da.
                            Risu scommo do
                            vive rramae cenasacc umsanla cusvel facilisis.
                        </p>
                        <div class="my-4">
                            <x-buttons.default-button></x-buttons.default-button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center py-3">
                <p class="kristi p-0 m-0" style="font-size: 8rem;color: #626262;letter-spacing: 1px;">Lorem ipsum dolor sit
                </p>
            </div>
            {{-- //segunda parte 2 section --}}

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
            </div>
        </div>
    </section>
    <section class="container-fluid p-0 section-tree d-flex align-items-center" style="overflow: unset;min-height: 70vh">
        <div class="container-fluid d-flex py-5 my-5">
            <div class="col-md-6">

            </div>
            <div class="col-md-5 d-flex flex-column justify-content-center" style="color: #626262;">
                <p class="kristi p-0 m-0" style="font-size: 6rem;letter-spacing: 1px;">Lorem Ipsom</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores delectus deleniti voluptas obcaecati
                    exercitationem distinctio ipsam nostrum ex dolore aliquam nihil ipsa, molestiae natus accusantium dolor,
                    mollitia quod? Ipsam, impedit.</p>
                <div class="my-4">
                    <x-buttons.default-button></x-buttons.default-button>
                </div>
            </div>
        </div>
    </section>
    <section class="container py-5 my-5">
        <div class="row">
            <div class="col-md-6 px-md-5">
                <x-images.image-container-one height="450px"></x-images.image-container-one>
            </div>
            <div class="col-md-6 px-md-5">
                <div class="w-100 h-100 d-flex flex-column justify-content-center">
                    <p class="kristi p-0 m-0" style="font-size: 6rem;color: #626262;letter-spacing: 1px;">Lorem Ipsom
                    </p>
                    <p>Lorem ipsum dolors
                        itamet,con
                        sectetur adipis cin gelit, sed doeius modte mpo rinci didun tut
                        lab ore etdo lorem ag naal iqua.Quisipsum
                        suspe ndis seultri cesgr avi da.
                        Risu scommo do
                        vive rramae cenasacc umsanla cusvel facilisis.
                    </p>
                    <div class="my-3 d-flex" style="flex-wrap: wrap;">
                        <x-buttons.values-one></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 50,00'"></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 30,00'"></x-buttons.values-one>
                        <x-buttons.values-one :value="'R$ 10,00'"></x-buttons.values-one>
                    </div>


                    <div class="my-4" style="overflow: unset;">
                        <x-buttons.values-two :value="'R$ 10,00'"></x-buttons.values-two>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5" style="background: #017dc3">
        <p class="kristi p-0 m-0 text-center" style="font-size: 6rem;color: #fff;letter-spacing: 1px;">Lorem Ipsom dolor
        </p>
        <div class="d-flex align-items-center justify-content-center">
            <div style="width: 80%">
                <div class="row text-center" style="color: #fff;font-weight: 200">
                    <div class="col-md-4">
                        <x-icons.bottom-bar-icons :pathImage="'/img/icons/socialmedias/GMAIL.png'">
                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cupiditate, vel adipisci
                                voluptatibus dolorem voluptates cum error nam.</p>
                        </x-icons.bottom-bar-icons >
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
