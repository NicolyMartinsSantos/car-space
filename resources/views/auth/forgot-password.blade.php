<x-guest-layout>
    <div class="mb-4">
        <span class="car-badge car-badge-teal mb-2">
            <i class="bi bi-key-fill"></i> Recuperação de acesso
        </span>
        <h1 class="h3 fw-bold mb-1">Redefinir senha</h1>
        <p class="text-secondary small mb-0">
            Esqueceu sua senha? Informe seu endereço de e-mail cadastrado e enviaremos um link para você escolher uma nova senha.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="car-form-label">
                <i class="bi bi-envelope"></i> E-mail
            </label>
            <input id="email" class="form-control car-form-control" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="btn-car-primary btn-car-lg w-100 mb-3">
            <i class="bi bi-send"></i> Enviar Link de Redefinição
        </button>

        <div class="text-center pt-3 border-top" style="border-color: var(--cs-border) !important;">
            <a href="{{ route('login') }}" class="fw-semibold cs-text-teal-dark text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> Voltar para o login
            </a>
        </div>
    </form>
</x-guest-layout>
