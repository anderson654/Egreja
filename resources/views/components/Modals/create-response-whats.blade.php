@component('components.Modals.template-modal')
    <div class="modal-body p-0">
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h3 class="font-weight-bolder text-primary text-gradient">Criar template</h3>
                <p class="mb-0">Addicione uma palavra chave a este grupo.</p>
            </div>
            <div class="card-body pb-3">
                <form role="form text-left" method="POST" action="{{ route('responses.store') }}">
                    @csrf
                    <label for="response">palavra chave</label>
                    <div class="input-group mb-3">
                        <input id='response' name="response" type="text" class="form-control" placeholder="Palavra" aria-label="response"
                            aria-describedby="name-addon" maxlength="255" value="{{ old('response') }}" required>
                    </div>
                    @error('response')
                        <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                    @enderror
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Adicionar palavra chave</button>
                    </div>
                    <input type="hidden" name="group_responses_id" id="exampleFormControlInput1" value="{{$groupResponse->id}}">
                </form>
            </div>
        </div>
    </div>
@endcomponent

