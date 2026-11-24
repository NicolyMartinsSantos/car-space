<section>
    <header class="mb-4 pb-3 border-bottom" style="border-color: var(--cs-border) !important;">
        <h2 class="h5 fw-bold mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-person-gear cs-text-teal"></i> Informações do Perfil
        </h2>
        <p class="text-secondary small mb-0">
            Atualize seu nome completo e endereço de e-mail cadastrado na conta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="name" class="car-form-label">
                    <i class="bi bi-person"></i> Nome Completo
                </label>
                <input id="name" name="name" type="text" class="form-control car-form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="col-md-6">
                <label for="email" class="car-form-label">
                    <i class="bi bi-envelope"></i> E-mail
                </label>
                <input id="email" name="email" type="email" class="form-control car-form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="cs-alert cs-alert-warning mb-4 d-block">
                <p class="small mb-1 fw-medium">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Seu endereço de e-mail ainda não foi verificado.
                </p>
                <button form="send-verification" class="btn btn-link p-0 text-decoration-none small fw-bold cs-text-navy">
                    Clique aqui para reenviar o e-mail de verificação.
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 mb-0 small fw-semibold" style="color: var(--cs-success-text);">
                        <i class="bi bi-check-circle-fill me-1"></i> Um novo link de verificação foi enviado para seu e-mail.
                    </p>
                @endif
            </div>
        @endif

        <div class="d-flex flex-wrap align-items-center gap-3">
            <button type="submit" class="btn-car-primary">
                <i class="bi bi-check-circle"></i> Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="car-badge car-badge-green"
                >
                    <i class="bi bi-check-circle-fill"></i> Salvo com sucesso.
                </span>
            @endif
        </div>
    </form>
</section>
