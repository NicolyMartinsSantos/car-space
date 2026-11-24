@extends('layouts.main')

@section('content')
@php
    $diasSemana = [
        'seg' => 'Seg',
        'ter' => 'Ter',
        'qua' => 'Qua',
        'qui' => 'Qui',
        'sex' => 'Sex',
        'sab' => 'Sáb',
        'dom' => 'Dom',
    ];
    $diasAtivos = (array)($anuncio->dias_disponiveis ?? []);
@endphp

<div class="container" style="max-width: 1080px;">
    <!-- Navegação de Retorno -->
    <div class="mb-3">
        <a href="/" class="btn-car-secondary btn-car-sm btn-car-pill">
            <i class="bi bi-arrow-left"></i> Voltar para Explorar
        </a>
    </div>

    <!-- Cabeçalho do Anúncio -->
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="car-badge car-badge-teal">
                    <i class="bi bi-p-square-fill"></i> {{ $anuncio->categoria_vaga ?? 'Garagem' }}
                </span>
                <span class="car-badge car-badge-navy">
                    <i class="bi bi-car-front-fill"></i> {{ $anuncio->quantidade_vagas }} {{ (int)$anuncio->quantidade_vagas === 1 ? 'vaga' : 'vagas' }}
                </span>
            </div>
            <h1 class="cs-page-title mb-2">{{ $anuncio->titulo }}</h1>
            <p class="cs-listing-address mb-0 fs-6">
                <i class="bi bi-geo-alt-fill"></i>
                <span>
                    {{ $anuncio->endereco }}@if(!empty($anuncio->cidade)) &middot; <strong class="cs-text-navy">{{ $anuncio->cidade }}</strong>@endif
                </span>
            </p>
        </div>

        <div class="text-md-end">
            <span class="text-muted small d-block">Valor da vaga</span>
            <span class="cs-price-value cs-price-lg">R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}</span>
        </div>
    </div>

    @php
        $fotosGaleria = ($anuncio->fotos ?? collect())
            ->filter(fn ($foto) => $foto->arquivoExiste())
            ->values();
    @endphp

    <!-- Galeria de Fotografias ou Espaço Reservado -->
    <div class="mb-4">
        @if($fotosGaleria->count() > 0)
            <div x-data="{
                    active: 0,
                    fotos: @js($fotosGaleria->map(fn($f) => ['id' => $f->id, 'url' => $f->url])->all()),
                    prev() { this.active = (this.active - 1 + this.fotos.length) % this.fotos.length; },
                    next() { this.active = (this.active + 1) % this.fotos.length; }
                 }"
                 @keydown.arrow-left.window="if (fotos.length > 1) prev()"
                 @keydown.arrow-right.window="if (fotos.length > 1) next()">
                <div class="cs-gallery-stage">
                    <img :src="fotos[active].url" :alt="'Fotografia ' + (active + 1) + ' de {{ addslashes($anuncio->titulo) }}'">

                    <template x-if="fotos.length > 1">
                        <div>
                            <button type="button" class="cs-gallery-nav cs-gallery-nav-prev" @click="prev()" aria-label="Foto anterior">
                                <i class="bi bi-chevron-left fs-5"></i>
                            </button>
                            <button type="button" class="cs-gallery-nav cs-gallery-nav-next" @click="next()" aria-label="Próxima foto">
                                <i class="bi bi-chevron-right fs-5"></i>
                            </button>
                        </div>
                    </template>

                    <span class="cs-photo-count-badge cs-tabular">
                        <i class="bi bi-images"></i>
                        <span x-text="(active + 1) + ' / ' + fotos.length"></span>
                    </span>
                </div>

                @if($fotosGaleria->count() > 1)
                    <div class="cs-gallery-thumbs">
                        @foreach($fotosGaleria as $index => $foto)
                            <button type="button"
                                    class="cs-gallery-thumb"
                                    :class="{ 'active': active === {{ $index }} }"
                                    @click="active = {{ $index }}"
                                    aria-label="Ver fotografia {{ $index + 1 }}">
                                <img src="{{ $foto->url }}" alt="Miniatura {{ $index + 1 }} de {{ $anuncio->titulo }}" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <x-cs-listing-photo-placeholder
                :categoria="$anuncio->categoria_vaga ?? 'Vaga de Garagem'"
                :vagas="$anuncio->quantidade_vagas"
                size="detail"
            />
        @endif
    </div>

    <div class="row g-4 align-items-start">
        <!-- Coluna Principal de Informações -->
        <div class="col-lg-8">
            <div class="car-card p-4 p-md-5 mb-4">
                <h2 class="h5 fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-card-text cs-text-teal"></i> Sobre esta vaga
                </h2>
                <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.75;">{{ $anuncio->descricao }}</p>

                <hr class="my-4" style="border-color: var(--cs-border);">

                <h2 class="h5 fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-grid-1x2 cs-text-teal"></i> Especificações da garagem
                </h2>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-4 border h-100" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                            <i class="bi bi-car-front-fill cs-text-teal fs-4 d-block mb-2"></i>
                            <span class="text-muted small d-block">Quantidade</span>
                            <strong class="cs-text-navy">{{ $anuncio->quantidade_vagas }} {{ (int)$anuncio->quantidade_vagas === 1 ? 'vaga' : 'vagas' }}</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-4 border h-100" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                            <i class="bi bi-tag-fill cs-text-teal fs-4 d-block mb-2"></i>
                            <span class="text-muted small d-block">Categoria</span>
                            <strong class="cs-text-navy">{{ $anuncio->categoria_vaga }}</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded-4 border h-100" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                            <i class="bi bi-clock-fill cs-text-teal fs-4 d-block mb-2"></i>
                            <span class="text-muted small d-block">Funcionamento</span>
                            <strong class="cs-text-navy cs-tabular">{{ substr($anuncio->horario_inicio, 0, 5) }} – {{ substr($anuncio->horario_fim, 0, 5) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Dias Disponíveis -->
                <div class="p-4 rounded-4 border" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <span class="fw-bold cs-text-navy d-inline-flex align-items-center gap-2">
                            <i class="bi bi-calendar-week-fill cs-text-teal"></i> Dias Disponíveis
                        </span>
                        <span class="text-muted small">
                            Atendimento semanal da garagem
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($diasSemana as $chave => $sigla)
                            @if(in_array($chave, $diasAtivos))
                                <span class="cs-day-chip-active">
                                    <i class="bi bi-check-circle-fill"></i> {{ $sigla }}
                                </span>
                            @else
                                <span class="cs-day-chip-inactive">
                                    <i class="bi bi-x-circle"></i> {{ $sigla }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Lateral Sticky de Reserva (Padrão Airbnb) -->
        <div class="col-lg-4">
            <div class="car-card p-4 sticky-top" style="top: 96px;">
                <div class="d-flex align-items-baseline justify-content-between pb-3 mb-3 border-bottom" style="border-color: var(--cs-border) !important;">
                    <div>
                        <span class="cs-price-value cs-price-lg">R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}</span>
                    </div>
                    <span class="car-badge car-badge-teal">
                        <i class="bi bi-check-circle-fill"></i> Disponível
                    </span>
                </div>

                <p class="text-secondary small mb-3">
                    Solicite sua reserva diretamente com o anunciante e acompanhe a confirmação em tempo real.
                </p>

                <div class="p-3 rounded-4 border mb-4" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small"><i class="bi bi-telephone cs-text-teal me-1"></i> Contato</span>
                        <strong class="cs-text-navy small cs-tabular">{{ $anuncio->telefone }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small"><i class="bi bi-clock cs-text-teal me-1"></i> Horário livre</span>
                        <strong class="cs-text-navy small cs-tabular">{{ substr($anuncio->horario_inicio, 0, 5) }} às {{ substr($anuncio->horario_fim, 0, 5) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-start gap-2 pt-2 mt-2 border-top" style="border-color: var(--cs-border) !important;">
                        <span class="text-secondary small flex-shrink-0"><i class="bi bi-calendar3 cs-text-teal me-1"></i> Dias livres</span>
                        <div class="text-end">
                            @if(count($diasAtivos) === 7)
                                <strong class="cs-text-navy small">Todos os dias</strong>
                            @elseif($diasAtivos === ['seg', 'ter', 'qua', 'qui', 'sex'])
                                <strong class="cs-text-navy small">Segunda a Sexta</strong>
                            @else
                                <div class="d-flex flex-wrap gap-1 justify-content-end">
                                    @foreach($diasAtivos as $dia)
                                        <span class="car-badge car-badge-teal py-1 px-2" style="font-size: 0.72rem;">
                                            {{ $diasSemana[$dia] ?? ucfirst($dia) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @guest
                    <a href="/login" class="btn-car-primary btn-car-lg w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar para Reservar
                    </a>
                @endguest

                @auth
                    @if($anuncio->user_id == auth()->id())
                        <div class="cs-alert cs-alert-info justify-content-center text-center mb-3 small">
                            <i class="bi bi-info-circle-fill cs-text-teal"></i>
                            <span>Este anúncio pertence a você.</span>
                        </div>
                        <a href="/anuncio/edit/{{ $anuncio->id }}" class="btn-car-outline-primary w-100">
                            <i class="bi bi-pencil"></i> Editar meu Anúncio
                        </a>
                    @else
                        <a href="/reserva/create?anuncio_id={{ $anuncio->id }}" class="btn-car-accent btn-car-lg w-100">
                            <i class="bi bi-calendar-check"></i> Solicitar Reserva
                        </a>
                    @endif
                @endauth

                <div class="mt-3 text-center">
                    <a href="/" class="text-secondary small text-decoration-none fw-medium">
                        <i class="bi bi-arrow-left me-1"></i> Voltar aos anúncios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
