@props([
    'anuncio',
    'size' => 'card',
])

@php
    $fotos = $anuncio->fotos ?? collect();
    $primeiraFoto = $fotos->first(fn ($foto) => $foto->arquivoExiste());
    $totalFotos = $fotos->filter(fn ($foto) => $foto->arquivoExiste())->count();
    $categoria = $anuncio->categoria_vaga ?? 'Garagem';
    $isMoto = strtolower((string) $categoria) === 'moto';
    $icon = $isMoto ? 'bi-bicycle' : 'bi-car-front-fill';
@endphp

@if($primeiraFoto)
    @if($size === 'thumb')
        <div {{ $attributes->merge(['class' => 'cs-listing-photo-thumb']) }}>
            <img src="{{ $primeiraFoto->url }}" alt="Foto de {{ $anuncio->titulo }}" loading="lazy">
            @if($totalFotos > 1)
                <span class="cs-photo-count-badge" style="bottom: 6px; right: 6px; padding: 2px 7px; font-size: 0.68rem;">
                    <i class="bi bi-images"></i> {{ $totalFotos }}
                </span>
            @endif
        </div>
    @else
        <div {{ $attributes->merge(['class' => 'cs-listing-photo-frame']) }}>
            <img src="{{ $primeiraFoto->url }}" alt="Foto de {{ $anuncio->titulo }}" loading="lazy">

            <div class="cs-photo-overlay-top">
                <span class="cs-glass-badge">
                    <i class="bi {{ $icon }}"></i> {{ $categoria }}
                </span>
                @if(!is_null($anuncio->quantidade_vagas))
                    <span class="cs-glass-badge">
                        <i class="bi bi-p-square-fill"></i> {{ $anuncio->quantidade_vagas }} {{ (int)$anuncio->quantidade_vagas === 1 ? 'vaga' : 'vagas' }}
                    </span>
                @endif
            </div>

            @if($totalFotos > 1)
                <span class="cs-photo-count-badge">
                    <i class="bi bi-images"></i> {{ $totalFotos }} fotos
                </span>
            @endif
        </div>
    @endif
@else
    <x-cs-listing-photo-placeholder
        :categoria="$categoria"
        :vagas="$anuncio->quantidade_vagas"
        :size="$size"
        {{ $attributes }}
    />
@endif
