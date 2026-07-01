<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

    <div class="d-flex justify-content-end mb-4">

        @guest
            <a href="/login" class="btn btn-outline-primary me-2">
                Entrar
            </a>

            <a href="/register" class="btn btn-primary">
                Cadastrar
            </a>
        @endguest

        @auth

            <span class="me-3 align-self-center">
                Olá, <strong>{{ Auth::user()->name }}</strong>
            </span>

            <a href="/profile" class="btn btn-outline-secondary me-2">
                Perfil
            </a>

            <form action="/logout" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                    Sair
                </button>
            </form>

        @endauth

    </div>

    <div class="text-center">

        <h1 class="display-3 fw-bold">
            CAR SPACE
        </h1>

        <p class="lead mt-4">
            Sistema de compartilhamento de vagas de garagem.
        </p>

        <p class="text-muted">
            Cadastre seus veículos, anuncie sua vaga ou reserve uma garagem.
        </p>

        <div class="mt-5 d-flex flex-wrap justify-content-center gap-3">

    @auth

        <a href="/veiculo" class="btn btn-primary btn-lg me-3">
            🚗 Meus veículos
        </a>

        <a href="/anuncio" class="btn btn-success btn-lg me-3">
            🏠 Meus Anúncios
        </a>

    @endauth

    <a href="/anuncio" class="btn btn-warning btn-lg me-3">
        🔎 Ver anúncios disponíveis
    </a>

    @auth

        <a href="/reserva" class="btn btn-dark btn-lg">
            📅 Minhas Reservas
        </a>

    @endauth

        </div>

    </div>

</div>