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

                    <div class="mx-4">
                        <label><select class="dataTable-selector me-2" id="selectTotalPerPage">
                                <option value="2">2</option>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome
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
                                                    {{ $prayerRequest->status->title ?? 'N/A' }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm font-weight-bold mb-0">
                                                    {{ $prayerRequest->created_at }}</p>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <p class="text-sm font-weight-bold mb-0">Edit</p>
                                                    <p class="text-sm font-weight-bold mb-0 ps-2">Delete</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>


                            <div class="pagination-container d-flex justify-content-center align-items-center">
                                <ul class="pagination pagination-secondary">
                                    <li class="page-item">
                                        <a class="page-link" href="javascript:;" aria-label="Previous" id="primerItem">
                                            <span aria-hidden="true"><i class="fa fa-angle-double-left"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript:;" aria-label="Previous" id="previous">
                                            <span aria-hidden="true"><i class="fa fa-angle-left"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>

                                    <div class="d-flex" id="paginate">
                                    </div>

                                    <li class="page-item">
                                        <a class="page-link" href="javascript:;" aria-label="Next" id="next">
                                            <span aria-hidden="true"><i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>

                                    <li class="page-item">
                                        <a class="page-link" href="javascript:;" aria-label="Next" id="ultimateItem">
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

@section('script')
    <script>
        new CustomDataTables({
            elementTable: '.table',
            elemetTotalPerPage: '#selectTotalPerPage',
            elementSearch: '#search',
            elementNext: '#next',
            elementPrevious: '#previous',
            elementFistPage: '#primerItem',
            elementLastPage: '#ultimateItem'
        });
    </script>
@endsection
