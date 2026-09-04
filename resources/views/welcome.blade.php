@extends('layouts.main')

@section('content')
<div class="container">
    <!-- Hero Banner -->
    <div class="hero-section text-center px-3">
        <div class="container py-3">
            <h1 class="fw-bold display-5 mb-3">Encontre a Vaga Perfeita para seu Veículo</h1>
            <p class="lead mb-0 opacity-90 mx-auto" style="max-width: 650px;">
                Alugue garagens seguras, bem localizadas e com os melhores preços. Simples, rápido e confiável.
            </p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold h3 mb-1 text-dark">Garagens Disponíveis</h2>
            <p class="text-muted mb-0 small">Confira as vagas publicadas recentemente na plataforma</p>
        </div>
        @auth
            <a href="/anuncio/create" class="btn-car-primary">
                <i class="bi bi-plus-circle"></i> Anunciar Vaga
            </a>
        @endauth
    </div>

    <div class="row g-4">
        @forelse($data as $anuncio)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="car-card h-100 d-flex flex-column">
                    <div class="p-3">
                        <div class="car-image-placeholder">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>{{ $anuncio->categoria_vaga ?? 'Garagem' }}</span>
                        </div>
                    </div>

                    <div class="car-card-body d-flex flex-column flex-grow-1 pt-0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="car-badge car-badge-blue">
                                <i class="bi bi-p-square"></i> {{ $anuncio->categoria_vaga ?? 'Vaga' }}
                            </span>
                            <span class="fw-bold text-primary fs-5">
                                R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                            </span>
                        </div>

                        <h3 class="h5 fw-bold text-dark mb-2">
                            {{ $anuncio->titulo }}
                        </h3>

                        <p class="text-secondary small mb-3 flex-grow-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $anuncio->endereco }}
                        </p>

                        <div class="d-flex align-items-center gap-3 text-muted small mb-3 pb-3 border-bottom">
                            <span>
                                <i class="bi bi-car-front-fill text-primary me-1"></i> {{ $anuncio->quantidade_vagas }} vaga(s)
                            </span>
                            <span>
                                <i class="bi bi-clock-fill text-primary me-1"></i> {{ substr($anuncio->horario_inicio, 0, 5) }} - {{ substr($anuncio->horario_fim, 0, 5) }}
                            </span>
                        </div>

                        <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-primary w-100 text-decoration-none">
                            <i class="bi bi-eye"></i> Ver Anúncio
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white p-5 rounded-4 shadow-sm border border-slate-200 inline-block mx-auto" style="max-width: 500px;">
                    <i class="bi bi-inbox text-muted display-1 mb-3 d-block"></i>
                    <h4 class="fw-bold text-dark">Nenhum anúncio cadastrado</h4>
                    <p class="text-secondary mb-4">Ainda não existem garagens disponíveis na sua região.</p>
                    @auth
                        <a href="/anuncio/create" class="btn-car-primary">
                            <i class="bi bi-plus-lg"></i> Cadastrar Primeiro Anúncio
                        </a>
                    @endauth
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection