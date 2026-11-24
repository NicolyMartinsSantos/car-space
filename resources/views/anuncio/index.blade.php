@extends('layouts.main')

@section('content')
<div class="container">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Meus Anúncios</h1>
            <p class="cs-page-subtitle">Gerencie suas vagas publicadas e responda às solicitações de reserva</p>
        </div>
        <a href="/anuncio/create" class="btn-car-primary btn-car-pill">
            <i class="bi bi-plus-lg"></i> Novo Anúncio
        </a>
    </div>

    <x-cs-flash-messages />

    @if(count($data) > 0)
        <div class="d-flex flex-column gap-4">
            @foreach($data as $anuncio)
                <article class="cs-owner-listing-card">
                    <!-- Cabeçalho / Resumo do Anúncio -->
                    <div class="cs-owner-listing-top">
                        <div class="d-flex flex-column flex-md-row align-items-start justify-content-between gap-3">
                            <div class="d-flex flex-column flex-sm-row align-items-start gap-3 flex-grow-1">
                                <x-cs-listing-cover :anuncio="$anuncio" size="thumb" />

                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <span class="text-muted small fw-bold cs-tabular">#{{ $anuncio->id }}</span>
                                        <span class="car-badge car-badge-teal">{{ $anuncio->categoria_vaga }}</span>
                                        <span class="car-badge car-badge-gray">
                                            <i class="bi bi-car-front"></i> {{ $anuncio->quantidade_vagas }} {{ (int)$anuncio->quantidade_vagas === 1 ? 'vaga' : 'vagas' }}
                                        </span>
                                    </div>

                                    <h2 class="h5 fw-bold mb-1">
                                        <a href="/anuncio/{{ $anuncio->id }}" class="text-decoration-none cs-text-navy">
                                            {{ $anuncio->titulo }}
                                        </a>
                                    </h2>

                                    <p class="cs-listing-address mb-2">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>
                                            {{ $anuncio->endereco }}@if(!empty($anuncio->cidade)) &middot; <strong class="cs-text-navy">{{ $anuncio->cidade }}</strong>@endif
                                        </span>
                                    </p>

                                    <div class="d-flex flex-wrap align-items-center gap-3 small text-secondary">
                                        <span class="cs-tabular">
                                            <i class="bi bi-clock cs-text-teal me-1"></i>
                                            {{ substr($anuncio->horario_inicio, 0, 5) }} – {{ substr($anuncio->horario_fim, 0, 5) }}
                                        </span>
                                        <span class="cs-tabular fw-bold cs-text-navy fs-6">
                                            R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ações do Anúncio -->
                            <div class="d-flex flex-wrap align-items-center gap-2 flex-shrink-0">
                                <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary btn-car-sm">
                                    <i class="bi bi-eye"></i> Visualizar
                                </a>

                                @guest
                                    <a href="/login" class="btn-car-primary btn-car-sm">
                                        Reservar
                                    </a>
                                @endguest

                                @auth
                                    @if($anuncio->user_id == auth()->id())
                                        <a href="/anuncio/edit/{{ $anuncio->id }}" class="btn-car-outline-primary btn-car-sm">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="/anuncio/delete/{{ $anuncio->id }}" class="btn-car-danger-soft" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">
                                            <i class="bi bi-trash"></i> Excluir
                                        </a>
                                    @else
                                        <a href="/reserva/create?anuncio_id={{ $anuncio->id }}" class="btn-car-primary btn-car-sm">
                                            <i class="bi bi-calendar-plus"></i> Reservar
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Painel de Solicitações de Reserva Recebidas -->
                    <div class="cs-owner-requests-panel">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-inbox-fill cs-text-teal"></i>
                                <span class="fw-bold cs-text-navy small">Solicitações de Reserva</span>
                                <span class="car-badge {{ $anuncio->reservas->count() > 0 ? 'car-badge-navy' : 'car-badge-gray' }}">
                                    {{ $anuncio->reservas->count() }}
                                </span>
                            </div>
                        </div>

                        @if($anuncio->reservas->count() > 0)
                            <div class="car-table-container">
                                <div class="table-responsive">
                                    <table class="table car-table">
                                        <thead>
                                            <tr>
                                                <th>Motorista</th>
                                                <th>Veículo</th>
                                                <th>Placa</th>
                                                <th>Data</th>
                                                <th>Horário</th>
                                                <th>Status</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($anuncio->reservas as $reserva)
                                                <tr>
                                                    <td class="fw-semibold cs-text-navy">
                                                        <i class="bi bi-person-circle cs-text-teal me-1"></i>
                                                        {{ $reserva->user?->name ?? 'Motorista não informado' }}
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-car-front text-secondary me-1"></i>
                                                        {{ $reserva->veiculo?->modelo ?? 'Veículo não informado' }}
                                                    </td>
                                                    <td>
                                                        <span class="cs-plate">{{ $reserva->veiculo?->placa ?? '-' }}</span>
                                                    </td>
                                                    <td class="cs-tabular">
                                                        <i class="bi bi-calendar3 text-secondary me-1"></i>
                                                        {{ $reserva->data ? date('d/m/Y', strtotime($reserva->data)) : '-' }}
                                                    </td>
                                                    <td class="cs-tabular">
                                                        <i class="bi bi-clock text-secondary me-1"></i>
                                                        {{ $reserva->horario ? substr($reserva->horario, 0, 5) : '-' }}
                                                    </td>
                                                    <td>
                                                        <x-cs-status-badge :status="$reserva->status" />
                                                    </td>
                                                    <td class="text-end">
                                                        @if(strtolower($reserva->status) == 'pendente')
                                                            <div class="d-inline-flex gap-2">
                                                                <form action="/reserva/{{ $reserva->id }}/aceitar" method="POST" class="d-inline m-0">
                                                                    @csrf
                                                                    <button type="submit" class="btn-car-success-soft" title="Aceitar reserva">
                                                                        <i class="bi bi-check-lg"></i> Aceitar
                                                                    </button>
                                                                </form>
                                                                <form action="/reserva/{{ $reserva->id }}/recusar" method="POST" class="d-inline m-0" onsubmit="return confirm('Tem certeza que deseja recusar esta solicitação?')">
                                                                    @csrf
                                                                    <button type="submit" class="btn-car-danger-soft" title="Recusar reserva">
                                                                        <i class="bi bi-x-lg"></i> Recusar
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="p-3 rounded-3 bg-white border text-secondary small d-flex align-items-center gap-2" style="border-color: var(--cs-border) !important;">
                                <i class="bi bi-info-circle cs-text-teal"></i>
                                <span>Nenhuma solicitação de reserva recebida para este anúncio até o momento.</span>
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="cs-empty-state">
            <div class="cs-empty-icon">
                <i class="bi bi-megaphone"></i>
            </div>
            <h3 class="h4 fw-bold mb-2">Você ainda não tem anúncios</h3>
            <p class="text-secondary mb-4">Publique sua vaga de garagem para começar a receber solicitações de reserva.</p>
            <a href="/anuncio/create" class="btn-car-primary btn-car-pill">
                <i class="bi bi-plus-lg"></i> Cadastrar Primeiro Anúncio
            </a>
        </div>
    @endif
</div>
@endsection
