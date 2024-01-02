@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pedidos de acompanhamento.'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Usuários</h6>
                    </div>
                    <div class="dataTable-top">
                        <div class="mx-4">
                            <label><select class="dataTable-selector me-2" id="selectTotalPerPage">
                                    <option value="5">5</option>
                                    <option value="10" selected="">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                </select><span style="vertical-align: inherit;"><span
                                        style="vertical-align: inherit;color:#8392ab;font-weight:400">entradas
                                        por página</span></span></label>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nome
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
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
                                        @foreach ($sideDidhes as $prayerRequest)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-3 py-1">
                                                        <div>
                                                            <img src="/img/team-2.jpg" class="avatar me-3" alt="image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">
                                                                {{ $prayerRequest->user->username ?? 'vazio' }}</h6>
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
                                                        {{ $prayerRequest->user->created_at ?? 'vazio' }}</p>
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
                                {{-- <div class="d-flex justify-content-center">
                                    <ul class="pagination pagination-secondary">
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $sideDidhes->url(1) }}" aria-label="Anterior">
                                                <span aria-hidden="true"><i class="fa fa-angle-double-left"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $sideDidhes->previousPageUrl() }}"
                                                aria-label="Previous">
                                                <span aria-hidden="true"><i class="fa fa-angle-left"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                        </li>

                                        @php
                                            $start = max(1, $sideDidhes->currentPage() - 2);
                                            $end = min($sideDidhes->lastPage(), $start + 4);
                                        @endphp

                                        @for ($i = $start; $i <= $end; $i++)
                                            <li class="page-item {{ $sideDidhes->currentPage() == $i ? 'active' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $sideDidhes->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        <li class="page-item">
                                            <a class="page-link" href="{{ $sideDidhes->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true"><i class="fa fa-angle-right"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $sideDidhes->url($sideDidhes->lastPage()) }}"
                                                aria-label="Próximo">
                                                <span aria-hidden="true"><i class="fa fa-angle-double-right"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div> --}}

                                <div class="pagination-container d-flex justify-content-center align-items-center">
                                    <ul class="pagination pagination-secondary">
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:;" aria-label="Previous" id="previous">
                                                <span aria-hidden="true"><i class="fa fa-angle-left"
                                                        aria-hidden="true"></i></span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:;">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:;">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:;">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:;">4</a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="page-link" href="javascript:;">5</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:;" aria-label="Next" id="next">
                                                <span aria-hidden="true"><i class="fa fa-angle-right"
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

    @section('script')
        <script>
            const table = new DataTable('.table', {
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    "info": "Mostrando _START_ até _END_ de _TOTAL_ entradas"
                    // Outras configurações de idioma...
                },
                lengthChange: false,
                dom: '',
                // searching: false,
                pageLength: 10,
                "order": [[3, 'desc']]
            });

            $(document).ready(function() {
                $("#selectTotalPerPage").on("change", function() {
                    // Atualiza o número de itens por página com base na seleção
                    var novoTamanho = $(this).val();
                    console.log(novoTamanho);
                    table.page.len(novoTamanho).draw();
                });
            });

            $("#search").on("keyup", function() {
                console.log(table.search($(this).val()));
                table.search($(this).val()).draw();
                // console.log("Itens exibidos: " + table.page.info().end - table.page.info().start + 1);
            });

            $('#next').on('click', function() {
                table.page('next').draw('page');
            });

            $('#previous').on('click', function() {
                table.page('previous').draw('page');
            });

        </script>
    @endsection
