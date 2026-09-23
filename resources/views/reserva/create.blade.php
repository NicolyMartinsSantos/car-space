@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Cadastrar Reserva</h1>
            <p class="text-muted mb-0 small">Escolha a vaga e seu veículo para efetuar o agendamento</p>
        </div>
        <a href="/reserva" class="btn-car-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/reserva/store" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-calendar3 text-primary me-1"></i> Data da Reserva</label>
                    <input type="date" name="data" class="form-control car-form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-clock text-primary me-1"></i> Horário</label>
                    <input type="time" name="horario" class="form-control car-form-control" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="car-form-label"><i class="bi bi-car-front text-primary me-1"></i> Selecione seu Veículo</label>
                <select name="veiculo_id" class="form-select car-form-select" required>
                    <option value="">Selecione o veículo...</option>
                    @foreach($veiculos as $veiculo)
                        <option value="{{ $veiculo->id }}">
                            {{ $veiculo->modelo }} ({{ $veiculo->placa }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="car-form-label"><i class="bi bi-p-square text-primary me-1"></i> Selecione o Anúncio / Vaga</label>
                <select name="anuncio_id" class="form-select car-form-select" required>
                    <option value="">Selecione a garagem...</option>
                    @foreach($anuncios as $anuncio)
                        <option value="{{ $anuncio->id }}">
                            {{ $anuncio->titulo }} — R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('anuncio_id')
                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="/reserva" class="btn-car-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-car-primary">
                    <i class="bi bi-check-circle"></i> Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>
@endsection