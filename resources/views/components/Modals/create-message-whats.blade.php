@component('components.Modals.template-modal')
    <div class="modal-body p-0">
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h3 class="font-weight-bolder text-primary text-gradient">Criar novo mensagem</h3>
                <p class="mb-0">Crie uma novo mensagen para ser enviada.</p>
                <p class="mb-0">(obs) as mensagens serão enviadas na ordem.</p>
            </div>
            <div class="card-body pb-3">
                <form role="form text-left" method="POST" action="{{ route('dialog-questions-watsapp.store') }}">
                    @csrf
                    <label for="editor">Digite a mensagem</label>
                    <div id="editor" name='brabo'>

                    </div>
                    @error('question')
                        <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                    @enderror
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Criar
                            template</button>
                    </div>
                    <input type="hidden" name="question" id="exampleFormControlInput1">
                    <input type="hidden" name="dialog_template_id" id="exampleFormControlInput1" value="{{$id}}">
                </form>
            </div>
        </div>
    </div>
@endcomponent
