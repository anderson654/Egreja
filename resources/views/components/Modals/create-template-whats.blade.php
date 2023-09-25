@component('components.Modals.template-modal')
    <div class="modal-body p-0">
        <div class="card card-plain">
            <div class="card-header pb-0 text-left">
                <h3 class="font-weight-bolder text-primary text-gradient">Criar template</h3>
                <p class="mb-0">Registre seu template e edite para criar um fluxo.</p>
            </div>
            <div class="card-body pb-3">
                <form role="form text-left" method="POST" action="{{ route('dialog-whatsapp.store') }}">
                    @csrf
                    <label for="name">Nome</label>
                    <div class="input-group mb-3">
                        <input id='name' name="name" type="text" class="form-control" placeholder="Nome" aria-label="Name"
                            aria-describedby="name-addon" maxlength="255" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                    @enderror
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-primary btn-lg btn-rounded w-100 mt-4 mb-0">Criar
                            template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcomponent
