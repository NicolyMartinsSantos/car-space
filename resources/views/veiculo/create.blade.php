@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Cadastrar Veículo</h1>
            <p class="text-muted mb-0 small">Adicione um novo veículo à sua conta</p>
        </div>
        <a href="/veiculo" class="btn-car-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/veiculo/store" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-card-heading text-primary me-1"></i> Placa do Veículo</label>
                    <input type="text" name="placa" placeholder="ABC-1234 ou ABC1D23" class="form-control car-form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-car-front text-primary me-1"></i> Modelo</label>
                    <input type="text" name="modelo" placeholder="Ex: Civic, Gol, Corolla, Titan 160" class="form-control car-form-control" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-palette text-primary me-1"></i> Cor</label>
                    <input type="text" name="cor" placeholder="Ex: Preto, Prata" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-calendar text-primary me-1"></i> Ano</label>
                    <input type="number" name="ano" placeholder="2022" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-grid text-primary me-1"></i> Categoria</label>
                    <select name="categoria" class="form-select car-form-select" required>
                        <option value="">Selecione...</option>
                        <option value="Carro">Carro</option>
                        <option value="Moto">Moto</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="/veiculo" class="btn-car-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-car-primary">
                    <i class="bi bi-check-circle"></i> Salvar Veículo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection