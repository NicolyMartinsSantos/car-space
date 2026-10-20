@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Minhas Reservas</h1>
            <p class="text-muted mb-0 small">Acompanhe suas solicitações e agendamentos de garagens</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/" class="btn-car-primary">
                <i class="bi bi-calendar-plus"></i> Nova Reserva
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(count($data) > 0)
        <div class="car-table-container">
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
                            <td class="fw-bold text-muted">#{{ $reserva->id }}</td>
                            <td class="fw-semibold text-dark"><i class="bi bi-calendar3 me-1 text-primary"></i> {{ date('d/m/Y', strtotime($reserva->data)) }}</td>
                            <td><i class="bi bi-clock me-1 text-secondary"></i> {{ substr($reserva->horario, 0, 5) }}</td>
                            <td>
                                @if(strtolower($reserva->status) == 'confirmada' || strtolower($reserva->status) == 'aprovado')
                                    <span class="car-badge car-badge-green"><i class="bi bi-check-circle-fill"></i> {{ $reserva->status }}</span>
                                @elseif(strtolower($reserva->status) == 'recusada')
                                    <span class="car-badge" style="background-color: #fee2e2; color: #991b1b;"><i class="bi bi-x-circle-fill"></i> {{ $reserva->status }}</span>
                                @elseif(strtolower($reserva->status) == 'cancelada')
                                    <span class="car-badge car-badge-gray"><i class="bi bi-x-circle-fill"></i> {{ $reserva->status }}</span>
                                @else
                                    <span class="car-badge car-badge-amber"><i class="bi bi-hourglass-split"></i> {{ $reserva->status }}</span>
                                @endif
                            </td>
                            <td><span class="fw-semibold text-dark"><i class="bi bi-car-front text-primary me-1"></i> {{ $reserva->veiculo?->modelo ?? 'Veículo não encontrado' }}</span></td>
                            <td><span class="text-secondary"><i class="bi bi-p-square text-primary me-1"></i> {{ $reserva->anuncio?->titulo ?? 'Anúncio não encontrado' }}</span></td>
                            <td class="text-end">
                                @if(in_array(strtolower($reserva->status), ['pendente', 'confirmada']))
                                    <form action="/reserva/{{ $reserva->id }}/cancelar" method="POST" class="d-inline m-0" onsubmit="return confirm('Deseja realmente cancelar esta reserva?')">
                                        @csrf
                                        <button type="submit" class="btn-car-danger py-1 px-3">
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
    @else
        <div class="car-card p-5 text-center">
            <i class="bi bi-calendar-check text-muted display-4 mb-3 d-block"></i>
            <h4 class="fw-bold text-dark">Nenhuma reserva encontrada</h4>
            <p class="text-secondary mb-4">Você ainda não solicitou reservas em nenhuma vaga de garagem.</p>
            <a href="/" class="btn-car-primary">
                <i class="bi bi-calendar-plus"></i> Fazer uma Reserva
            </a>
        </div>
    @endif
</div>
@endsection