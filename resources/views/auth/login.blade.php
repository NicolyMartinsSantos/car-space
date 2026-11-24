<x-guest-layout>
    <div class="mb-4">
        <span class="car-badge car-badge-teal mb-2">
            <i class="bi bi-shield-lock-fill"></i> Acesso seguro
        </span>
        <h1 class="h3 fw-bold mb-1">Entrar na sua conta</h1>
        <p class="text-secondary small mb-0">Informe seu e-mail e senha para acessar o Car Space</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="car-form-label">
                <i class="bi bi-envelope"></i> E-mail
            </label>
            <input id="email" class="form-control car-form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="car-form-label mb-0">
                    <i class="bi bi-lock"></i> Senha
                </label>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small fw-semibold cs-text-teal-dark" href="{{ route('password.request') }}">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>
            <div class="position-relative" x-data="{ show: false }">
                <input id="password"
                       class="form-control car-form-control pe-5"
                       type="password"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="Digite sua senha" />
                <button type="button"
                        @click="show = !show"
                        class="btn border-0 text-secondary position-absolute top-50 end-0 translate-middle-y me-2 p-1 bg-transparent shadow-none"
                        :aria-label="show ? 'Ocultar senha' : 'Exibir senha'"
                        :title="show ? 'Ocultar senha' : 'Exibir senha'">
                    <i class="bi fs-5" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-secondary small">
                Lembrar de mim neste dispositivo
            </label>
        </div>

        <button type="submit" class="btn-car-primary btn-car-lg w-100 mb-3">
            <i class="bi bi-box-arrow-in-right"></i> Entrar
        </button>

        <div class="text-center pt-3 border-top" style="border-color: var(--cs-border) !important;">
            <span class="text-secondary small">Ainda não tem conta?</span>
            <a href="{{ route('register') }}" class="fw-bold cs-text-teal-dark text-decoration-none small ms-1">
                Criar Conta
            </a>
        </div>
    </form>
</x-guest-layout>
