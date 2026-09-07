<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="h4 fw-bold text-dark mb-1">Acessar sua Conta</h1>
        <p class="text-muted small">Informe suas credenciais para entrar no sistema</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="car-form-label"><i class="bi bi-envelope text-primary me-1"></i> E-mail</label>
            <input id="email" class="form-control car-form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="car-form-label mb-0"><i class="bi bi-lock text-primary me-1"></i> Senha</label>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control car-form-control" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-secondary small">
                Lembrar de mim
            </label>
        </div>

        <button type="submit" class="btn-car-primary w-100 py-2 justify-content-center mb-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
        </button>

        <div class="text-center pt-3 border-top">
            <span class="text-secondary small">Ainda não tem conta?</span>
            <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none small ms-1">
                Criar Conta
            </a>
        </div>
    </form>
</x-guest-layout>
