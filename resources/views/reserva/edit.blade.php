@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 740px;">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Editar Reserva</h1>
            <p class="cs-page-subtitle">Atualize os detalhes da reserva <strong class="cs-text-navy">#{{ $reserva->id }}</strong></p>
        </div>
        <a href="/reserva" class="btn-car-secondary btn-car-sm btn-car-pill">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/reserva/update/{{ $reserva->id }}" method="POST">
            @csrf

            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-calendar-event"></i> Data, Horário e Status
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-calendar3"></i> Data da Reserva</label>
                        <input type="date" name="data" value="{{ $reserva->data }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-clock"></i> Horário</label>
                        <input type="time" name="horario" value="{{ $reserva->horario }}" class="form-control car-form-control" required>
                    </div>
                </div>

                <div class="p-3 rounded-4 border d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-semibold cs-text-navy">Status atual:</span>
                        <x-cs-status-badge :status="$reserva->status" />
                    </div>
                    <small class="text-muted fst-italic">O status é controlado pelo proprietário da vaga.</small>
                </div>
            </div>

            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-car-front-fill"></i> Veículo e Garagem
                </div>

                <div class="mb-3">
                    <label class="car-form-label"><i class="bi bi-car-front"></i> Veículo</label>
                    <select name="veiculo_id" class="form-select car-form-select" required>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id }}" {{ $reserva->veiculo_id == $veiculo->id ? 'selected' : '' }}>
                                {{ $veiculo->modelo }} ({{ $veiculo->placa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="car-form-label"><i class="bi bi-p-square"></i> Anúncio / Vaga</label>
                    <select name="anuncio_id" class="form-select car-form-select" required>
                        @foreach($anuncios as $anuncio)
                            <option value="{{ $anuncio->id }}" {{ $reserva->anuncio_id == $anuncio->id ? 'selected' : '' }}>
                                {{ $anuncio->titulo }} — R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top" style="border-color: var(--cs-border) !important;">
                <a href="/reserva" class="btn-car-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-car-primary">
                    <i class="bi bi-check-circle"></i> Atualizar Reserva
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
