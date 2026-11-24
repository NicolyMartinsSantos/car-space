@extends('layouts.main')

@section('content')
<div class="container">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Minhas Reservas</h1>
            <p class="cs-page-subtitle">Acompanhe o status de todas as suas solicitações e agendamentos de garagem</p>
        </div>
        <a href="/" class="btn-car-primary btn-car-pill">
            <i class="bi bi-calendar-plus"></i> Nova Reserva
        </a>
    </div>

    <x-cs-flash-messages />

    @if(count($data) > 0)
        <div class="car-table-container">
            <div class="table-responsive">
                <table class="table car-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th>Veículo</th>
                            <th>Anúncio / Garagem</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $reserva)
                            <tr>
                                <td class="fw-bold text-muted cs-tabular">#{{ $reserva->id }}</td>
                                <td class="fw-semibold cs-text-navy cs-tabular">
                                    <i class="bi bi-calendar3 cs-text-teal me-1"></i>
                                    {{ date('d/m/Y', strtotime($reserva->data)) }}
                                </td>
                                <td class="cs-tabular text-secondary">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ substr($reserva->horario, 0, 5) }}
                                </td>
                                <td>
                                    <x-cs-status-badge :status="$reserva->status" />
                                </td>
                                <td>
                                    <span class="fw-semibold cs-text-navy">
                                        <i class="bi bi-car-front cs-text-teal me-1"></i>
                                        {{ $reserva->veiculo?->modelo ?? 'Veículo não encontrado' }}
                                    </span>
                                </td>
                                <td>
                                    @if($reserva->anuncio)
                                        <a href="/anuncio/{{ $reserva->anuncio->id }}" class="text-decoration-none fw-medium cs-text-navy">
                                            <i class="bi bi-p-square cs-text-teal me-1"></i> {{ $reserva->anuncio->titulo }}
                                        </a>
                                    @else
                                        <span class="text-secondary">
                                            <i class="bi bi-p-square text-muted me-1"></i> Anúncio não encontrado
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(in_array(strtolower($reserva->status), ['pendente', 'confirmada']))
                                        <form action="/reserva/{{ $reserva->id }}/cancelar" method="POST" class="d-inline m-0" onsubmit="return confirm('Deseja realmente cancelar esta reserva?')">
                                            @csrf
                                            <button type="submit" class="btn-car-danger-soft">
                                                <i class="bi bi-x-circle"></i> Cancelar reserva
                                            </button>
                                        </form>
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
        <div class="cs-empty-state">
            <div class="cs-empty-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h3 class="h4 fw-bold mb-2">Nenhuma reserva encontrada</h3>
            <p class="text-secondary mb-4">Você ainda não solicitou reservas em nenhuma vaga de garagem.</p>
            <a href="/" class="btn-car-primary btn-car-pill">
                <i class="bi bi-compass"></i> Explorar Garagens
            </a>
        </div>
    @endif
</div>
@endsection
