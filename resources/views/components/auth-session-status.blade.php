@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'cs-alert cs-alert-success']) }} role="status">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>
        <span>{{ $status }}</span>
    </div>
@endif
