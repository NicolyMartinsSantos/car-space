@extends('layouts.main')

@section('content')
<div class="container">
    <x-cs-flash-messages />

    <!-- Hero Banner Comercial -->
    <section class="hero-section">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-12 col-lg-7">
                <div class="cs-hero-eyebrow">
                    <i class="bi bi-geo-alt-fill"></i> Compartilhamento inteligente de garagens
                </div>
                <h1 class="cs-hero-title">
                    Encontre a vaga <span>perfeita</span> para o seu veículo.
                </h1>
                <p class="cs-hero-subtitle mb-4">
                    Descubra garagens disponíveis em ótima localização, com valores transparentes, horários claros e solicitação de reserva direta com o anunciante.
                </p>

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <a href="#garagens-disponiveis" class="btn-car-accent btn-car-lg btn-car-pill">
                        <i class="bi bi-search"></i> Explorar Vagas
                    </a>
                    @auth
                        <a href="/anuncio/create" class="btn-car-outline-light btn-car-lg btn-car-pill">
                            <i class="bi bi-plus-circle"></i> Anunciar minha Vaga
                        </a>
                    @else
                        <a href="/register" class="btn-car-outline-light btn-car-lg btn-car-pill">
                            <i class="bi bi-person-plus"></i> Criar Conta Gratuita
                        </a>
                    @endauth
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="cs-hero-highlights">
                    <div class="cs-hero-stat-card">
                        <span class="cs-hero-stat-icon"><i class="bi bi-p-square-fill"></i></span>
                        <div class="cs-hero-stat-value cs-tabular">{{ count($data) }}</div>
                        <div class="cs-hero-stat-label">{{ count($data) === 1 ? 'Vaga disponível' : 'Vagas disponíveis' }}</div>
                    </div>
                    <div class="cs-hero-stat-card">
                        <span class="cs-hero-stat-icon"><i class="bi bi-clock-history"></i></span>
                        <div class="cs-hero-stat-value">Horários</div>
                        <div class="cs-hero-stat-label">Dias e turnos claros</div>
                    </div>
                    <div class="cs-hero-stat-card">
                        <span class="cs-hero-stat-icon"><i class="bi bi-shield-check"></i></span>
                        <div class="cs-hero-stat-value">Direto</div>
                        <div class="cs-hero-stat-label">Reserva simples e segura</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Barra de Busca por Cidade -->
    @php
        $cidadeBusca = $cidadeBusca ?? '';
    @endphp
    <section id="garagens-disponiveis" class="mb-4">
        <form action="/" method="GET" class="cs-search-bar d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2" role="search" aria-label="Buscar vagas por cidade">
            <div class="cs-search-input-wrap">
                <i class="bi bi-geo-alt-fill"></i>
                <input
                    type="search"
                    name="cidade"
                    value="{{ $cidadeBusca }}"
                    placeholder="Pesquisar garagens por cidade (ex.: Matinhos, Pontal do Paraná, Curitiba...)"
                    class="cs-search-input"
                    aria-label="Pesquisar garagens por cidade"
                >
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                @if($cidadeBusca !== '')
                    <a href="/" class="btn-car-secondary btn-car-pill flex-grow-1 flex-md-grow-0">
                        <i class="bi bi-x-lg"></i> Limpar
                    </a>
                @endif
                <button type="submit" class="btn-car-primary btn-car-pill flex-grow-1 flex-md-grow-0 px-4">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </section>

    <!-- Cabeçalho da Vitrine -->
    <div class="cs-page-header">
        <div>
            <h2 class="cs-page-title">
                @if($cidadeBusca !== '')
                    Garagens em "{{ $cidadeBusca }}"
                @else
                    Garagens Disponíveis
                @endif
            </h2>
            <p class="cs-page-subtitle">
                @if($cidadeBusca !== '')
                    @if(count($data) > 0)
                        Encontrado(s) <strong class="cs-text-navy">{{ count($data) }}</strong> {{ count($data) === 1 ? 'anúncio' : 'anúncios' }} para sua pesquisa
                    @else
                        Nenhuma vaga encontrada para a cidade pesquisada
                    @endif
                @elseif(count($data) > 0)
                    Exibindo <strong class="cs-text-navy">{{ count($data) }}</strong> {{ count($data) === 1 ? 'espaço publicado' : 'espaços publicados' }} na plataforma
                @else
                    Confira as vagas publicadas recentemente na plataforma
                @endif
            </p>
        </div>
        @auth
            <a href="/anuncio/create" class="btn-car-primary btn-car-pill">
                <i class="bi bi-plus-circle"></i> Anunciar Vaga
            </a>
        @endauth
    </div>

    <!-- Grid de Anúncios Padronizados -->
    @php
        $mapaDiasCurto = [
            'seg' => 'Seg', 'ter' => 'Ter', 'qua' => 'Qua',
            'qui' => 'Qui', 'sex' => 'Sex', 'sab' => 'Sáb', 'dom' => 'Dom',
        ];
    @endphp

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($data as $anuncio)
            @php
                $diasAtivos = (array)($anuncio->dias_disponiveis ?? []);
                if (count($diasAtivos) === 7) {
                    $resumoDias = 'Todos os dias';
                } elseif ($diasAtivos === ['seg', 'ter', 'qua', 'qui', 'sex']) {
                    $resumoDias = 'Seg a Sex';
                } elseif (count($diasAtivos) > 0) {
                    $resumoDias = implode(', ', array_map(fn($d) => $mapaDiasCurto[$d] ?? ucfirst($d), array_slice($diasAtivos, 0, 3)))
                        . (count($diasAtivos) > 3 ? ' +' . (count($diasAtivos) - 3) : '');
                } else {
                    $resumoDias = 'Consultar dias';
                }
            @endphp

            <div class="col">
                <article class="car-card car-card-interactive h-100 d-flex flex-column">
                    <div class="p-3 pb-0">
                        <a href="/anuncio/{{ $anuncio->id }}" class="text-decoration-none d-block" aria-label="Ver detalhes de {{ $anuncio->titulo }}">
                            <x-cs-listing-cover :anuncio="$anuncio" />
                        </a>
                    </div>

                    <div class="car-card-body d-flex flex-column flex-grow-1">
                        <h3 class="cs-listing-title mb-2">
                            <a href="/anuncio/{{ $anuncio->id }}" class="text-decoration-none cs-text-navy">
                                {{ $anuncio->titulo }}
                            </a>
                        </h3>

                        <p class="cs-listing-address mb-3 flex-grow-1">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>
                                {{ $anuncio->endereco }}@if(!empty($anuncio->cidade)) &middot; <strong class="cs-text-navy">{{ $anuncio->cidade }}</strong>@endif
                            </span>
                        </p>

                        <div class="cs-listing-meta mb-3 pb-3 border-bottom" style="border-color: var(--cs-border) !important;">
                            <span class="cs-meta-chip cs-tabular">
                                <i class="bi bi-clock"></i>
                                {{ substr($anuncio->horario_inicio, 0, 5) }} – {{ substr($anuncio->horario_fim, 0, 5) }}
                            </span>
                            <span class="cs-meta-chip">
                                <i class="bi bi-calendar-week"></i>
                                {{ $resumoDias }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-2 pt-1">
                            <div class="cs-price-block">
                                <span class="cs-price-value">R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}</span>
                            </div>
                            <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-primary btn-car-sm btn-car-pill">
                                Ver Anúncio <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="cs-empty-state mx-auto" style="max-width: 560px;">
                    <div class="cs-empty-icon">
                        <i class="bi {{ $cidadeBusca !== '' ? 'bi-search' : 'bi-p-square' }}"></i>
                    </div>
                    @if($cidadeBusca !== '')
                        <h3 class="h4 fw-bold mb-2">Nenhuma garagem encontrada em "{{ $cidadeBusca }}"</h3>
                        <p class="text-secondary mb-4">
                            Tente pesquisar por outro nome de cidade ou limpe o filtro para visualizar todas as garagens disponíveis.
                        </p>
                        <a href="/" class="btn-car-primary btn-car-pill">
                            <i class="bi bi-arrow-counterclockwise"></i> Ver Todos os Anúncios
                        </a>
                    @else
                        <h3 class="h4 fw-bold mb-2">Nenhum anúncio disponível no momento</h3>
                        <p class="text-secondary mb-4">
                            Ainda não existem garagens publicadas disponíveis para reserva. Volte em breve ou anuncie a primeira vaga da plataforma.
                        </p>
                        @auth
                            <a href="/anuncio/create" class="btn-car-primary btn-car-pill">
                                <i class="bi bi-plus-lg"></i> Cadastrar Primeiro Anúncio
                            </a>
                        @else
                            <a href="/register" class="btn-car-primary btn-car-pill">
                                <i class="bi bi-person-plus"></i> Criar Conta para Anunciar
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
