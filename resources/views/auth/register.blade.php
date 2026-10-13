<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="h4 fw-bold text-dark mb-1">Criar Nova Conta</h1>
        <p class="text-muted small">Cadastre-se para anunciar ou reservar garagens</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="car-form-label"><i class="bi bi-person text-primary me-1"></i> Nome Completo</label>
            <input id="name" class="form-control car-form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Seu nome" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="car-form-label"><i class="bi bi-envelope text-primary me-1"></i> E-mail</label>
            <input id="email" class="form-control car-form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="car-form-label"><i class="bi bi-lock text-primary me-1"></i> Senha</label>
            <div class="position-relative" x-data="{ show: false }">
                <input id="password" 
                       class="form-control car-form-control pe-5" 
                       type="password" 
                       :type="show ? 'text' : 'password'" 
                       name="password" 
                       required 
                       autocomplete="new-password" 
                       placeholder="Mínimo 8 caracteres" />
                <button type="button" 
                        @click="show = !show" 
                        class="btn border-0 text-secondary position-absolute top-50 end-0 translate-middle-y me-2 p-1 bg-transparent shadow-none" 
                        :aria-label="show ? 'Ocultar senha' : 'Exibir senha'" 
                        :title="show ? 'Ocultar senha' : 'Exibir senha'">
                    <i class="bi fs-5" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <div class="form-text text-muted small mt-1">
                <i class="bi bi-info-circle me-1"></i>
                Dica: evite senhas fáceis de adivinhar e não reutilize senhas de outros sites.
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="car-form-label"><i class="bi bi-shield-check text-primary me-1"></i> Confirmar Senha</label>
            <div class="position-relative" x-data="{ show: false }">
                <input id="password_confirmation" 
                       class="form-control car-form-control pe-5" 
                       type="password" 
                       :type="show ? 'text' : 'password'" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password" 
                       placeholder="Repita a senha" />
                <button type="button" 
                        @click="show = !show" 
                        class="btn border-0 text-secondary position-absolute top-50 end-0 translate-middle-y me-2 p-1 bg-transparent shadow-none" 
                        :aria-label="show ? 'Ocultar senha' : 'Exibir senha'" 
                        :title="show ? 'Ocultar senha' : 'Exibir senha'">
                    <i class="bi fs-5" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="btn-car-primary w-100 py-2 justify-content-center mb-3">
            <i class="bi bi-person-plus me-1"></i> Finalizar Cadastro
        </button>

        <div class="text-center pt-3 border-top">
            <span class="text-secondary small">Já possui uma conta?</span>
            <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none small ms-1">
                Entrar
            </a>
        </div>
    </form>
</x-guest-layout>
