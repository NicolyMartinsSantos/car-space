<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

    <nav class="navbar navbar-expand-lg bg-white shadow-sm mb-5">
    <div class="container">

        <a class="navbar-brand fw-bold text-primary fs-3" href="/">
            CAR SPACE
        </a>

        <div class="ms-auto d-flex align-items-center">

            @guest

                <a href="/login" class="btn btn-outline-primary me-2">
                    Entrar
                </a>

                <a href="/register" class="btn btn-primary">
                    Criar Conta
                </a>

            @endguest

            @auth

                <span class="me-3 text-secondary">
                    Olá, <strong>{{ Auth::user()->name }}</strong>
                </span>

                <a href="/profile" class="btn btn-outline-primary me-2">
                    Perfil
                </a>

                <form action="/logout" method="POST">
                    @csrf

                    <button class="btn btn-outline-danger">
                        Sair
                    </button>

                </form>

            @endauth

        </div>

    </div>
</nav>

   

    <hr class="my-5">

    <h2 class="text-center mb-4">
        Garagens Disponíveis
    </h2>

    <div class="row">

        @forelse($data as $anuncio)

            <div class="col-md-4 mb-4">

                <div class="card border-0 shadow rounded-4 h-100">

                <div class="card-body">

        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center mb-3"
             style="height:180px;">

            <span class="text-secondary">
                Foto da garagem
            </span>

        </div>

        <h5 class="card-title">
            {{ $anuncio->titulo }}
        </h5>

                        <p class="card-text">
                            📍 {{ $anuncio->endereco }}
                        </p>

                        <p class="card-text">
                            💰 R$ {{ $anuncio->preco }}
                        </p>

                        <p class="card-text">
                            🚗 {{ $anuncio->quantidade_vagas }} vaga(s)
                        </p>

                        <a href="/anuncio/{{ $anuncio->id }}"
                            class="btn btn-warning w-100">

                            Ver anúncio

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center">

                <h5>
                    Nenhum anúncio cadastrado.
                </h5>

            </div>

        @endforelse

    </div>

</div>