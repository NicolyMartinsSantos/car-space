@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 740px;">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Editar Veículo</h1>
            <p class="cs-page-subtitle">Atualize as informações do veículo <strong class="cs-text-navy">#{{ $veiculo->id }}</strong></p>
        </div>
        <a href="/veiculo" class="btn-car-secondary btn-car-sm btn-car-pill">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/veiculo/update/{{ $veiculo->id }}" method="POST">
            @csrf

            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-car-front-fill"></i> Identificação do Veículo
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-card-heading"></i> Placa do Veículo</label>
                        <input type="text" name="placa" value="{{ $veiculo->placa }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-car-front"></i> Modelo</label>
                        <input type="text" name="modelo" value="{{ $veiculo->modelo }}" class="form-control car-form-control" required>
                    </div>
                </div>
            </div>

            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-sliders"></i> Características
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-palette"></i> Cor</label>
                        <input type="text" name="cor" value="{{ $veiculo->cor }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-calendar"></i> Ano</label>
                        <input type="number" name="ano" value="{{ $veiculo->ano }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-grid"></i> Categoria</label>
                        <select name="categoria" class="form-select car-form-select" required>
                            <option value="Carro" {{ $veiculo->categoria == 'Carro' ? 'selected' : '' }}>Carro</option>
                            <option value="Moto" {{ $veiculo->categoria == 'Moto' ? 'selected' : '' }}>Moto</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top" style="border-color: var(--cs-border) !important;">
                <a href="/veiculo" class="btn-car-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-car-primary">
                    <i class="bi bi-check-circle"></i> Atualizar Veículo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
