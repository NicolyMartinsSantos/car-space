@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Meus Anúncios</h1>
            <p class="text-muted mb-0 small">Gerencie suas vagas de garagem publicadas</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/anuncio/create" class="btn-car-primary">
                <i class="bi bi-plus-lg"></i> Novo Anúncio
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
                        <th>Título</th>
                        <th>Endereço</th>
                        <th>Preço</th>
                        <th>Vagas</th>
                        <th>Categoria</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $anuncio)
                        <tr>
                            <td class="fw-bold text-muted">#{{ $anuncio->id }}</td>
                            <td class="fw-semibold text-dark">{{ $anuncio->titulo }}</td>
                            <td><i class="bi bi-geo-alt text-danger me-1"></i> {{ $anuncio->endereco }}</td>
                            <td class="fw-bold text-primary">R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}</td>
                            <td><span class="car-badge car-badge-gray"><i class="bi bi-car-front"></i> {{ $anuncio->quantidade_vagas }}</span></td>
                            <td><span class="car-badge car-badge-blue">{{ $anuncio->categoria_vaga }}</span></td>
                            <td class="text-end">
                                @guest
                                    <a href="/login" class="btn-car-primary btn-sm py-1 px-3">
                                        Reservar
                                    </a>
                                @endguest

                                @auth
                                    @if($anuncio->user_id == auth()->id())
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="/anuncio/edit/{{ $anuncio->id }}" class="btn-car-outline-primary py-1 px-3">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <a href="/anuncio/delete/{{ $anuncio->id }}" class="btn-car-danger py-1 px-3" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">
                                                <i class="bi bi-trash"></i> Excluir
                                            </a>
                                        </div>
                                    @else
                                        <a href="/reserva/create" class="btn-car-primary py-1 px-3">
                                            <i class="bi bi-calendar-plus"></i> Reservar
                                        </a>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="bg-light p-3 border-bottom">
                                <div class="px-2">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-inbox text-primary"></i>
                                        <span class="fw-semibold text-dark small">Solicitações de Reserva:</span>
                                        @if($anuncio->reservas->count() > 0)
                                            <span class="badge bg-secondary small">{{ $anuncio->reservas->count() }}</span>
                                        @endif
                                    </div>

                                    @if($anuncio->reservas->count() > 0)
                                        <div class="table-responsive bg-white rounded-3 border">
                                            <table class="table table-sm align-middle mb-0 small">
                                                <thead class="table-light text-muted">
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
                                                            <td class="fw-semibold text-dark">
                                                                <i class="bi bi-person-circle text-secondary me-1"></i>
                                                                {{ $reserva->user?->name ?? 'Motorista não informado' }}
                                                            </td>
                                                            <td>
                                                                <i class="bi bi-car-front text-secondary me-1"></i>
                                                                {{ $reserva->veiculo?->modelo ?? 'Veículo não informado' }}
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark border">
                                                                    {{ $reserva->veiculo?->placa ?? '-' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <i class="bi bi-calendar3 text-secondary me-1"></i>
                                                                {{ $reserva->data ? date('d/m/Y', strtotime($reserva->data)) : '-' }}
                                                            </td>
                                                            <td>
                                                                <i class="bi bi-clock text-secondary me-1"></i>
                                                                {{ $reserva->horario ? substr($reserva->horario, 0, 5) : '-' }}
                                                            </td>
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
                                                            <td class="text-end">
                                                                @if(strtolower($reserva->status) == 'pendente')
                                                                    <div class="d-inline-flex gap-1">
                                                                        <form action="/reserva/{{ $reserva->id }}/aceitar" method="POST" class="d-inline m-0">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-success py-1 px-2 fw-semibold" style="font-size: 0.75rem;" title="Aceitar reserva">
                                                                                <i class="bi bi-check-lg"></i> Aceitar
                                                                            </button>
                                                                        </form>
                                                                        <form action="/reserva/{{ $reserva->id }}/recusar" method="POST" class="d-inline m-0" onsubmit="return confirm('Tem certeza que deseja recusar esta solicitação?')">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 fw-semibold" style="font-size: 0.75rem;" title="Recusar reserva">
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
                                    @else
                                        <p class="text-muted small mb-0 fst-italic">
                                            <i class="bi bi-info-circle me-1"></i> Nenhuma solicitação de reserva para este anúncio.
                                        </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="car-card p-5 text-center">
            <i class="bi bi-mega-phone text-muted display-4 mb-3 d-block"></i>
            <h4 class="fw-bold text-dark">Você ainda não tem anúncios</h4>
            <p class="text-secondary mb-4">Comece a rentabilizar suas vagas dispondo-as para aluguel.</p>
            <a href="/anuncio/create" class="btn-car-primary">
                <i class="bi bi-plus-lg"></i> Cadastrar Anúncio
            </a>
        </div>
    @endif
</div>
@endsection