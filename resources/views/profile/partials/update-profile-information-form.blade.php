<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold text-dark mb-1">
            <i class="bi bi-person-gear text-primary me-1"></i> Informações do Perfil
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

        <div class="mb-3">
            <label for="name" class="car-form-label"><i class="bi bi-person text-primary me-1"></i> Nome Completo</label>
            <input id="name" name="name" type="text" class="form-control car-form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <label for="email" class="car-form-label"><i class="bi bi-envelope text-primary me-1"></i> E-mail</label>
            <input id="email" name="email" type="email" class="form-control car-form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-warning-subtle rounded-3">
                    <p class="text-sm text-dark mb-1">
                        Seu endereço de e-mail ainda não foi verificado.
                    </p>
                    <button form="send-verification" class="btn btn-link text-primary p-0 text-decoration-none small">
                        Clique aqui para reenviar o e-mail de verificação.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success small fw-semibold">
                            Um novo link de verificação foi enviado para seu e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn-car-primary">
                <i class="bi bi-check-circle"></i> Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-success small fw-semibold"
                >
                    <i class="bi bi-check2 me-1"></i> Salvo com sucesso.
                </span>
            @endif
        </div>
    </form>
</section>
