<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold text-danger mb-1">
            <i class="bi bi-exclamation-triangle text-danger me-1"></i> Excluir Conta
        </h2>
        <p class="text-secondary small mb-0">
            Assim que sua conta for excluída, todos os recursos e dados associados a ela serão permanentemente removidos.
        </p>
    </header>

    <button
        type="button"
        class="btn-car-danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <i class="bi bi-trash me-1"></i> Excluir Minha Conta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4 p-md-5">
            @csrf
            @method('delete')

            <h2 class="h5 fw-bold text-dark mb-2">
                Tem certeza que deseja excluir sua conta?
            </h2>

            <p class="text-secondary small mb-4">
                Esta ação é irreversível. Por favor, insira sua senha abaixo para confirmar que deseja excluir permanentemente sua conta do Car Space.
            </p>

            <div class="mb-4">
                <label for="password" class="car-form-label"><i class="bi bi-lock text-danger me-1"></i> Confirme sua Senha</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control car-form-control"
                    placeholder="Sua senha atual"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn-car-secondary" x-on:click="$dispatch('close')">
                    Cancelar
                </button>
                <button type="submit" class="btn-car-danger">
                    Excluir Conta Definitivamente
                </button>
            </div>
        </form>
    </x-modal>
</section>
