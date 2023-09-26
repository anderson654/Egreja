@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Voluntarios'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="text" id="template-id" value="{{ $dialogsTemplate->id }}" style="display: none">
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
                            {{ $dialogsTemplate->title }}
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
                            <div class="d-flex">
                                <div class="dropdown">
                                    <button class="btn bg-gradient-secondary dropdown-toggle me-2" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalSignUp">Nova mensagem</a></li>
                                        <li><a class="dropdown-item" href="{{route('group-responses.create')}}">Novo grupo</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-success" id="saveOrder" disabled>Salvar</button>
                            </div>
                            @include('components.Modals.create-message-whats')
                            @include('components.Modals.assigment-group-response-to-message')
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
                                            finish
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            <p class="text-xs font-weight-bold mb-0">Atribuir</p>
                                            grupo de mensagens
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table-options">
                                    @if ($dialogsTemplate->dialog_questions->count() === 0)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6>Nenhum registro</h6>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
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
                                                        <span class="badge"
                                                            style="font-size: 10px;background: #b0eed3;color: #1aae6f">{{ $group_questions_response->group_response->title }}</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="align-middle">
                                                @foreach ($questions->group_questions_responses as $group_questions_response)
                                                    @if ($group_questions_response->group_response->responses_role_id === 3)
                                                        <span class="badge"
                                                            style="font-size: 10px;background: #fdd1da;color: #f80031">{{ $group_questions_response->group_response->title }}</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="align-middle">
                                                <span class="material-icons modal-add-group" style="cursor: pointer" data-questionid={{$questions->id}}>
                                                    add
                                                </span>
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
        {{-- <select class="js-choice" multiple> --}}
        @include('layouts.footers.auth.footer')
        <script>
            $(document).ready(function() {
                //add function open modal
                $('.modal-add-group').click(function (){
                    $('#modal-assingn').modal('show');
                    $('[name="question_id"]').val($(this).data('questionid'));
                });

                //multiselect
                const choices = new Choices($('.js-choice')[0],{
                    removeItemButton: true,
                });
                //javascript
                const tableOptions = document.getElementById("table-options");
                let initialStateTable = null;
                let finalStateTable = null;
                let finalArrayUpdates = null;
                //se der problema ajustar a ordem
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
                                id: $(evt.to).children().eq(index).data('questionid'),
                                index: index + 1
                            }
                        });

                        setFinalUpdates(initialStateTable, finalStateTable);
                    }
                });

                function setFinalUpdates(initialArray, finalArray) {
                    //verifica se tem diferença entre os dois arrays
                    finalArrayUpdates = finalArray.filter((obj, index) => {
                        if (obj.id !== initialArray[index].id) {
                            return true
                        }
                        return false;
                    })
                    if (finalArrayUpdates.length === 0) {
                        $('#saveOrder').prop('disabled', true);
                        return;
                    }
                    $('#saveOrder').prop('disabled', false);
                }
                $('#saveOrder').click(function() {
                    saveOrder(finalArrayUpdates);
                })
            });

            function saveOrder(array) {

                const templateId = $('#template-id').val();
                const data = {
                    updates: array
                }

                $.ajax({
                    url: `/admin/dialog-questions-watsapp/${templateId}/updateorder`,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Define o cabeçalho CSRF-Token
                    },
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(response) {
                        $('#saveOrder').prop('disabled', true);
                        // Lida com a resposta do servidor, se necessário
                        // lineTable.fadeOut(500);
                    },
                    error: function(error) {
                        // Lida com erros, se houver
                        console.error(error);
                    },
                    complete: function() {
                        // Esta função será executada independentemente de sucesso ou erro
                        console.log('Solicitação concluída.');
                        $("#exampleModalToggle").modal("hide");

                        // Execute outras ações aqui
                    }
                });
            }
        </script>
    </div>
@endsection
