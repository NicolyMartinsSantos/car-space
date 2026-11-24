@props(['status'])

@php
    $normalized = strtolower((string) $status);
@endphp

@if($normalized === 'confirmada' || $normalized === 'aprovado')
    <span class="car-badge car-badge-green">
        <i class="bi bi-check-circle-fill"></i> {{ $status }}
    </span>
@elseif($normalized === 'recusada')
    <span class="car-badge car-badge-red">
        <i class="bi bi-x-circle-fill"></i> {{ $status }}
    </span>
@elseif($normalized === 'cancelada')
    <span class="car-badge car-badge-gray">
        <i class="bi bi-slash-circle-fill"></i> {{ $status }}
    </span>
@else
    <span class="car-badge car-badge-amber">
        <i class="bi bi-hourglass-split"></i> {{ $status }}
    </span>
@endif
