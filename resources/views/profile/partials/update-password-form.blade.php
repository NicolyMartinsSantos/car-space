<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold text-dark mb-1">
            <i class="bi bi-shield-lock text-primary me-1"></i> Atualizar Senha
        </h2>
        <p class="text-secondary small mb-0">
            Garanta a segurança de sua conta utilizando uma senha forte e segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="car-form-label"><i class="bi bi-key text-primary me-1"></i> Senha Atual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control car-form-control" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="car-form-label"><i class="bi bi-lock text-primary me-1"></i> Nova Senha</label>
            <input id="update_password_password" name="password" type="password" class="form-control car-form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="car-form-label"><i class="bi bi-shield-check text-primary me-1"></i> Confirmar Nova Senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control car-form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn-car-primary">
                <i class="bi bi-shield-check"></i> Alterar Senha
            </button>

            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-success small fw-semibold"
                >
                    <i class="bi bi-check2 me-1"></i> Senha atualizada com sucesso.
                </span>
            @endif
        </div>
    </form>
</section>
