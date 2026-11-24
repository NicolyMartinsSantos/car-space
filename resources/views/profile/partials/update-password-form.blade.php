<section>
    <header class="mb-4 pb-3 border-bottom" style="border-color: var(--cs-border) !important;">
        <h2 class="h5 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock cs-text-teal"></i> Atualizar Senha
        </h2>
        <p class="text-secondary small mb-0">
            Garanta a segurança da sua conta utilizando uma senha forte e exclusiva.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="car-form-label">
                <i class="bi bi-key"></i> Senha Atual
            </label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control car-form-control" autocomplete="current-password" placeholder="Digite sua senha atual" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="update_password_password" class="car-form-label">
                    <i class="bi bi-lock"></i> Nova Senha
                </label>
                <input id="update_password_password" name="password" type="password" class="form-control car-form-control" autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="col-md-6">
                <label for="update_password_password_confirmation" class="car-form-label">
                    <i class="bi bi-shield-check"></i> Confirmar Nova Senha
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control car-form-control" autocomplete="new-password" placeholder="Repita a nova senha" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <button type="submit" class="btn-car-primary">
                <i class="bi bi-shield-check"></i> Alterar Senha
            </button>

            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="car-badge car-badge-green"
                >
                    <i class="bi bi-check-circle-fill"></i> Senha atualizada com sucesso.
                </span>
            @endif
        </div>
    </form>
</section>
