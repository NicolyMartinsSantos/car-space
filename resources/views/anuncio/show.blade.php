<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h2 class="mb-0">
                        🏠 {{ $anuncio->titulo }}
                    </h2>

                </div>

                <div class="card-body">

                    <p class="fs-5">
                        📝 <strong>Descrição:</strong><br>
                        {{ $anuncio->descricao }}
                    </p>

                    <hr>

                    <p>
                        📍 <strong>Endereço:</strong><br>
                        {{ $anuncio->endereco }}
                    </p>

                    <p>
                        💰 <strong>Preço:</strong>
                        R$ {{ $anuncio->preco }}
                    </p>

                    <p>
                        🚗 <strong>Vagas:</strong>
                        {{ $anuncio->quantidade_vagas }}
                    </p>

                    <p>
                        🏷️ <strong>Categoria:</strong>
                        {{ $anuncio->categoria_vaga }}
                    </p>

                    <p>
                        🕒 <strong>Horário:</strong>
                        {{ $anuncio->horario_inicio }}
                        às
                        {{ $anuncio->horario_fim }}
                    </p>

                    <hr>

                    <div class="d-flex gap-2">

                        @guest

                            <a href="/login" class="btn btn-primary flex-fill">
                                Reservar
                            </a>

                        @endguest

                        @auth

                            <a href="/reserva/create" class="btn btn-primary flex-fill">
                                Reservar
                            </a>

                        @endauth

                        <a href="/" class="btn btn-secondary">
                            Voltar
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>