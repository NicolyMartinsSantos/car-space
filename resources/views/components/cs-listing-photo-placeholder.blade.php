@props([
    'categoria' => 'Garagem',
    'vagas' => null,
    'size' => 'card',
])

@php
    $isMoto = strtolower((string) $categoria) === 'moto';
    $icon = $isMoto ? 'bi-bicycle' : 'bi-car-front-fill';
    $sizeClass = match($size) {
        'detail' => 'car-image-placeholder-lg',
        'thumb' => 'car-image-placeholder-sm',
        default => '',
    };
@endphp

<div {{ $attributes->merge(['class' => 'car-image-placeholder ' . $sizeClass]) }}>
    @if($size !== 'thumb')
        <div class="cs-photo-overlay-top">
            <span class="cs-glass-badge">
                <i class="bi {{ $icon }}"></i> {{ $categoria ?: 'Garagem' }}
            </span>
            @if(!is_null($vagas))
                <span class="cs-glass-badge">
                    <i class="bi bi-p-square-fill"></i> {{ $vagas }} {{ (int)$vagas === 1 ? 'vaga' : 'vagas' }}
                </span>
            @endif
        </div>
    @endif

    <div class="cs-photo-icon-ring" @if($size === 'thumb') style="width: 42px; height: 42px; font-size: 1.2rem; margin-bottom: 4px; border-radius: 12px;" @endif>
        <i class="bi {{ $icon }}"></i>
    </div>
    <span class="cs-photo-caption" @if($size === 'thumb') style="font-size: 0.65rem;" @endif>
        {{ $size === 'thumb' ? ($categoria ?: 'Garagem') : 'Sem fotografias cadastradas' }}
    </span>
    @if($size === 'detail')
        <span class="cs-photo-subcaption">O anunciante ainda não adicionou fotos desta garagem</span>
    @endif
</div>
