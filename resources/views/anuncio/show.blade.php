@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 900px;">
    <div class="mb-4">
        <a href="/" class="btn-car-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left"></i> Voltar para Início
        </a>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <span class="car-badge car-badge-blue mb-2">
                    <i class="bi bi-p-square"></i> {{ $anuncio->categoria_vaga ?? 'Garagem' }}
                </span>
                <h1 class="fw-bold h2 text-dark mb-1">{{ $anuncio->titulo }}</h1>
                <p class="text-secondary mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $anuncio->endereco }}</p>
            </div>
            <div class="text-lg-end">
                <span class="text-muted small d-block">Preço</span>
                <span class="fw-bold text-primary fs-3">R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="car-card p-4 mb-4">
                <div class="car-image-placeholder mb-4" style="height: 240px;">
                    <i class="bi bi-shield-lock-fill display-3"></i>
                    <span class="mt-2">{{ $anuncio->categoria_vaga ?? 'Vaga de Garagem' }}</span>
                </div>

                <h2 class="h5 fw-bold text-dark mb-3">Descrição da Vaga</h2>
                <p class="text-secondary leading-relaxed mb-4" style="white-space: pre-line;">
                    {{ $anuncio->descricao }}
                </p>

                <hr class="my-4">

                <h2 class="h5 fw-bold text-dark mb-3">Detalhes e Especificações</h2>
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <i class="bi bi-car-front-fill text-primary fs-4 d-block mb-1"></i>
                            <span class="text-muted small d-block">Quantidade</span>
                            <strong class="text-dark">{{ $anuncio->quantidade_vagas }} vaga(s)</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <i class="bi bi-tag-fill text-primary fs-4 d-block mb-1"></i>
                            <span class="text-muted small d-block">Categoria</span>
                            <strong class="text-dark">{{ $anuncio->categoria_vaga }}</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <i class="bi bi-clock-fill text-primary fs-4 d-block mb-1"></i>
                            <span class="text-muted small d-block">Funcionamento</span>
                            <strong class="text-dark">{{ substr($anuncio->horario_inicio, 0, 5) }} - {{ substr($anuncio->horario_fim, 0, 5) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="car-card p-4 sticky-top" style="top: 90px;">
                <h3 class="h5 fw-bold text-dark mb-3">Reservar esta vaga</h3>
                <p class="text-muted small mb-4">Garantia de vaga reservada diretamente com o proprietário.</p>

                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary small">Contato:</span>
                        <strong class="text-dark small">{{ $anuncio->telefone }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary small">Horário livre:</span>
                        <strong class="text-dark small">{{ substr($anuncio->horario_inicio, 0, 5) }} às {{ substr($anuncio->horario_fim, 0, 5) }}</strong>
                    </div>
                </div>

                @guest
                    <a href="/login" class="btn-car-primary w-100 py-3 text-decoration-none">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar para Reservar
                    </a>
                @endguest

                @auth
                    <a href="/reserva/create" class="btn-car-primary w-100 py-3 text-decoration-none">
                        <i class="bi bi-calendar-check"></i> Solicitar Reserva
                    </a>
                @endauth

                <div class="mt-3 text-center">
                    <a href="/" class="text-secondary small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Voltar aos anúncios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection