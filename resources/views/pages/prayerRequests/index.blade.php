@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Gestão de pedidos de ajuda'])
    {{-- <div id="alert">
        @include('components.alert')
    </div> --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Usuários</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Telefone
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Create Date</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="./img/team-1.jpg" class="avatar me-3" alt="image">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">Admin</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">Admin</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">22/03/2022</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <p class="text-sm font-weight-bold mb-0">Edit</p>
                                                <p class="text-sm font-weight-bold mb-0 ps-2">Delete</p>
                                            </div>
                                        </td>
                                    </tr> --}}
                                    @foreach ($prayerRequests as $prayerRequest)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div>
                                                        <img src="/img/team-2.jpg" class="avatar me-3" alt="image">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $prayerRequest->user->username }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">
                                                    {{ $prayerRequest->user->phone ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">
                                                    {{ $prayerRequest->status_id ?? 'N/A' }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm font-weight-bold mb-0">
                                                    {{ $prayerRequest->user->created_at }}</p>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <p class="text-sm font-weight-bold mb-0">Edit</p>
                                                    <p class="text-sm font-weight-bold mb-0 ps-2">Delete</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="./img/team-3.jpg" class="avatar me-3" alt="image">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">Member</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">Member</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">22/03/2022</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <p class="text-sm font-weight-bold mb-0 cursor-pointer">Edit</p>
                                                <p class="text-sm font-weight-bold mb-0 ps-2 cursor-pointer">Delete</p>
                                            </div>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                <ul class="pagination pagination-secondary">
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $prayerRequests->url(1) }}" aria-label="Anterior">
                                            <span aria-hidden="true"><i class="fa fa-angle-double-left"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $prayerRequests->previousPageUrl() }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true"><i class="fa fa-angle-left"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>

                                    @php
                                        $start = max(1, $prayerRequests->currentPage() - 2);
                                        $end = min($prayerRequests->lastPage(), $start + 4);
                                    @endphp

                                    @for ($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $prayerRequests->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="{{ $prayerRequests->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <li class="page-item">
                                        <a class="page-link" href="{{ $prayerRequests->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true"><i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $prayerRequests->url($prayerRequests->lastPage()) }}"
                                            aria-label="Próximo">
                                            <span aria-hidden="true"><i class="fa fa-angle-double-right"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
