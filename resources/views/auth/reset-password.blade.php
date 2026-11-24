<x-guest-layout>
    <div class="mb-4">
        <span class="car-badge car-badge-teal mb-2">
            <i class="bi bi-shield-check"></i> Nova senha
        </span>
        <h1 class="h3 fw-bold mb-1">Definir nova senha</h1>
        <p class="text-secondary small mb-0">Informe seu e-mail e escolha uma nova senha segura para sua conta.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="car-form-label">
                <i class="bi bi-envelope"></i> E-mail
            </label>
            <input id="email" class="form-control car-form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="car-form-label">
                <i class="bi bi-lock"></i> Nova Senha
            </label>
            <input id="password" class="form-control car-form-control" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="car-form-label">
                <i class="bi bi-shield-check"></i> Confirmar Nova Senha
            </label>
            <input id="password_confirmation" class="form-control car-form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repita a nova senha" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="btn-car-primary btn-car-lg w-100">
            <i class="bi bi-check-circle"></i> Redefinir Senha
        </button>
    </form>
</x-guest-layout>
