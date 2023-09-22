@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Voluntarios'])
    <div class="card shadow-lg mx-4 card-profile-bottom mt-5">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{$dialogsTemplate->title}}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            Template de mensagens WhatsApp
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>Mensagens</h6>
                            <a href="{{ route('voluntary.create') }}">
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">
                                    <i class="ni ni-fat-add text-ligth text-sm opacity-10"></i>
                                    Adicionar template
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-secondary opacity-7"></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Mensagem</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            <p class="text-xs font-weight-bold mb-0">Messages</p>
                                            accept
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            <p class="text-xs font-weight-bold mb-0">Messages</p>
                                            reject
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            <p class="text-xs font-weight-bold mb-0">Messages</p>
                                            finish
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table-options">
                                    @foreach ($dialogsTemplate->dialog_questions as $questions)
                                        <tr data-questionid="{{ $questions->id }}">
                                            <td class="move">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <span class="material-icons" style="cursor: pointer">
                                                            open_with
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $questions->question }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach ($questions->group_questions_responses as $group_questions_response)
                                                    @if ($group_questions_response->group_response->responses_role_id === 1)
                                                        <p class="text-xs font-weight-bold mb-0">{{$group_questions_response->group_response->title}}</p>
                                                        <p class="text-xs text-secondary mb-0">Organization</p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    Edit
                                                </a>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
        <script>
            $(document).ready(function() {

                //javascript
                const tableOptions = document.getElementById("table-options");
                let initialStateTable = null;
                let finalStateTable = null;
                let finalArrayUpdates = null;

                var sortableList = new Sortable(tableOptions, {
                    handle: '.move',
                    animation: 150,
                    onChoose: function( /**Event*/ evt) {
                        if (!initialStateTable) {
                            const table = $("#table-options").clone();
                            initialStateTable = Array.from({
                                length: 3
                            }).map((_, index) => {
                                return {
                                    id: table.children().eq(index).data('questionid'),
                                    index: index + 1
                                }
                            });
                            finalStateTable = [...initialStateTable];
                        }
                    },
                    onEnd: function(evt) {
                        finalStateTable = finalStateTable.map((obj, index) => {
                            return {
                                ...obj,
                                index: $(evt.to).children().eq(index).data('questionid')
                            }
                        });
                        setFinalUpdates(initialStateTable, finalStateTable);
                    }
                });

                function setFinalUpdates(initialArray, finalArray) {
                    //verifica se tem diferença entre os dois arrays
                    finalArrayUpdates = finalArray.filter((obj, index) => {
                        if (obj.index !== initialArray[index].index) {
                            return true
                        }
                        return false;
                    })
                }
            });
        </script>
    </div>
@endsection
