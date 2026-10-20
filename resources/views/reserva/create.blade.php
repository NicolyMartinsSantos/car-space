@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 700px;">
    @if(isset($anuncio) && $anuncio)
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="fw-bold h2 mb-1 text-dark">Solicitar Reserva</h1>
                <p class="text-muted mb-0 small">Preencha as informações para agendar sua vaga</p>
            </div>
            <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="car-card p-4 p-md-5">
            <form action="/reserva/store" method="POST">
                @csrf

                <!-- Resumo da Garagem Selecionada -->
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small text-uppercase fw-bold">
                            <i class="bi bi-p-square text-primary me-1"></i> Garagem Selecionada
                        </span>
                        <span class="fw-bold text-primary fs-6">
                            R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $anuncio->titulo }}</h5>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $anuncio->endereco }}
                    </p>
                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-car-front text-primary me-1"></i> {{ $anuncio->quantidade_vagas }} vaga(s)</span>
                        <span><i class="bi bi-clock text-primary me-1"></i> {{ substr($anuncio->horario_inicio, 0, 5) }} às {{ substr($anuncio->horario_fim, 0, 5) }}</span>
                    </div>
                    <input type="hidden" name="anuncio_id" value="{{ $anuncio->id }}">
                    @error('anuncio_id')
                        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-calendar3 text-primary me-1"></i> Data da Reserva</label>
                        <input type="date" name="data" value="{{ old('data') }}" class="form-control car-form-control" required>
                        @error('data')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-clock text-primary me-1"></i> Horário</label>
                        <input type="time" name="horario" value="{{ old('horario') }}" class="form-control car-form-control" required>
                        @error('horario')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="car-form-label"><i class="bi bi-car-front text-primary me-1"></i> Selecione seu Veículo</label>
                    @if(count($veiculos) > 0)
                        <select name="veiculo_id" class="form-select car-form-select" required>
                            <option value="">Selecione o veículo...</option>
                            @foreach($veiculos as $veiculo)
                                <option value="{{ $veiculo->id }}" {{ old('veiculo_id') == $veiculo->id ? 'selected' : '' }}>
                                    {{ $veiculo->modelo }} ({{ $veiculo->placa }})
                                </option>
                            @endforeach
                        </select>
                        @error('veiculo_id')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    @else
                        <div class="alert alert-warning small mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Você ainda não possui nenhum veículo cadastrado.
                            <a href="/veiculo/create" class="alert-link ms-1">Cadastrar veículo agora</a>.
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-car-primary" {{ count($veiculos) == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle"></i> Confirmar Reserva
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="fw-bold h2 mb-1 text-dark">Solicitar Reserva</h1>
                <p class="text-muted mb-0 small">Escolha uma vaga disponível na plataforma</p>
            </div>
            <a href="/" class="btn-car-secondary">
                <i class="bi bi-arrow-left"></i> Voltar ao Início
            </a>
        </div>

        <div class="car-card p-5 text-center">
            <i class="bi bi-p-square text-primary display-4 mb-3 d-block"></i>
            <h4 class="fw-bold text-dark">Selecione uma garagem</h4>
            <p class="text-secondary mb-4">Para solicitar uma reserva, primeiro escolha uma das garagens disponíveis na página inicial.</p>
            <a href="/" class="btn-car-primary">
                <i class="bi bi-grid-fill"></i> Ver Garagens Disponíveis
            </a>
        </div>
    @endif
</div>
@endsection