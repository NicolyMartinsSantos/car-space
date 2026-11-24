<x-guest-layout>
    <div class="mb-4">
        <span class="car-badge car-badge-teal mb-2">
            <i class="bi bi-envelope-check-fill"></i> Verificação de e-mail
        </span>
        <h1 class="h3 fw-bold mb-1">Verifique seu e-mail</h1>
        <p class="text-secondary small mb-0">
            Obrigado por se cadastrar! Antes de começar, confirme seu endereço de e-mail clicando no link enviado para você. Caso não tenha recebido, podemos enviar outro.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="cs-alert cs-alert-success mb-4" role="status">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span>Um novo link de verificação foi enviado para o endereço de e-mail cadastrado.</span>
        </div>
    @endif

    <div class="d-flex flex-column gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-car-primary btn-car-lg w-100">
                <i class="bi bi-send"></i> Reenviar E-mail de Verificação
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="btn btn-link text-secondary small text-decoration-none fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i> Sair da conta
            </button>
        </form>
    </div>
</x-guest-layout>
