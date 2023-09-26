@component('components.Modals.template-modal',['id' => 'modal-assingn'])
    <div class="modal-body p-0">
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h3 class="font-weight-bolder text-primary text-gradient">Adicione grupos a sua mensagen.</h3>
                <p class="mb-0">Adicione os grupos que você criou a esta mensagem.</p>
            </div>
            <div class="card-body pb-3">
                <form role="form text-left" method="POST" action="{{ route('group-to-questions.store') }}">
                    @csrf
                    <label for="editor">Adicione grupos a sua mensagen</label>
                    <select class="form-control js-choice" name="ids-groups[]" id="civilState" placeholder="Estado civil" multiple>
                        @foreach ($groupResponses as $groupResponse)
                        <option value="{{$groupResponse->id}}">
                            {{$groupResponse->title}}</option>
                        @endforeach
                        </option>
                    </select>
                    @error('question')
                        <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                    @enderror
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Salvar grupos</button>
                    </div>
                    <input type="hidden" name="question" id="exampleFormControlInput1">
                    <input type="hidden" name="question_id" id="exampleFormControlInput1" value=1>
                </form>
            </div>
        </div>
    </div>
@endcomponent
