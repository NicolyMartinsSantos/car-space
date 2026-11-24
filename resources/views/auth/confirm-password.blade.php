<x-guest-layout>
    <div class="mb-4">
        <span class="car-badge car-badge-teal mb-2">
            <i class="bi bi-shield-lock-fill"></i> Confirmação de segurança
        </span>
        <h1 class="h3 fw-bold mb-1">Confirmar senha</h1>
        <p class="text-secondary small mb-0">
            Esta é uma área segura da aplicação. Confirme sua senha antes de continuar.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="car-form-label">
                <i class="bi bi-lock"></i> Senha
            </label>
            <input id="password"
                   class="form-control car-form-control"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="Digite sua senha atual" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="btn-car-primary btn-car-lg w-100">
            <i class="bi bi-check-circle"></i> Confirmar
        </button>
    </form>
</x-guest-layout>
