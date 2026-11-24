@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'list-unstyled mb-0 small fw-medium', 'style' => 'color: var(--cs-danger-text);']) }}>
        @foreach ((array) $messages as $message)
            <li class="d-flex align-items-center gap-1">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
