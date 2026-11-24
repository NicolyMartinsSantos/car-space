<section>
    <header class="mb-4 pb-3 border-bottom" style="border-color: var(--cs-danger-border) !important;">
        <h2 class="h5 fw-bold mb-1 d-flex align-items-center gap-2" style="color: var(--cs-danger-text);">
            <i class="bi bi-exclamation-triangle-fill"></i> Excluir Conta
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
        <i class="bi bi-trash"></i> Excluir Minha Conta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4 p-md-5">
            @csrf
            @method('delete')

            <div class="d-flex align-items-center gap-2 mb-2" style="color: var(--cs-danger-text);">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <h2 class="h5 fw-bold mb-0" style="color: var(--cs-navy-900);">
                    Tem certeza que deseja excluir sua conta?
                </h2>
            </div>

            <p class="text-secondary small mb-4">
                Esta ação é irreversível. Por favor, insira sua senha abaixo para confirmar que deseja excluir permanentemente sua conta do Car Space.
            </p>

            <div class="mb-4">
                <label for="password" class="car-form-label">
                    <i class="bi bi-lock" style="color: var(--cs-danger-text);"></i> Confirme sua Senha
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control car-form-control"
                    placeholder="Sua senha atual"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: var(--cs-border) !important;">
                <button type="button" class="btn-car-secondary" x-on:click="$dispatch('close')">
                    Cancelar
                </button>
                <button type="submit" class="btn-car-danger">
                    <i class="bi bi-trash"></i> Excluir Conta Definitivamente
                </button>
            </div>
        </form>
    </x-modal>
</section>
