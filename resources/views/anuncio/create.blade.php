@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Cadastrar Anúncio</h1>
            <p class="text-muted mb-0 small">Preencha as informações para disponibilizar sua vaga</p>
        </div>
        <a href="/anuncio" class="btn-car-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/anuncio/store" method="POST">
            @csrf

            <div class="mb-4">
                <label class="car-form-label"><i class="bi bi-fonts text-primary me-1"></i> Título do Anúncio</label>
                <input type="text" name="titulo" placeholder="Ex: Vaga coberta no centro da cidade" class="form-control car-form-control" required>
            </div>

            <div class="mb-4">
                <label class="car-form-label"><i class="bi bi-card-text text-primary me-1"></i> Descrição Detalhada</label>
                <textarea name="descricao" rows="4" placeholder="Descreva os detalhes da sua vaga, facilidades de acesso, segurança, etc." class="form-control car-form-control" required></textarea>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="car-form-label"><i class="bi bi-geo-alt text-primary me-1"></i> Endereço Completo</label>
                    <input type="text" name="endereco" placeholder="Rua, Número, Bairro - Cidade" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-currency-dollar text-primary me-1"></i> Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" placeholder="0.00" class="form-control car-form-control" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-telephone text-primary me-1"></i> Telefone / WhatsApp</label>
                    <input type="text" name="telefone" placeholder="(00) 00000-0000" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-car-front text-primary me-1"></i> Quantidade de Vagas</label>
                    <input type="number" name="quantidade_vagas" placeholder="1" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-tag text-primary me-1"></i> Categoria da Vaga</label>
                    <select name="categoria_vaga" class="form-select car-form-select" required>
                        <option value="">Selecione...</option>
                        <option value="Carro">Carro</option>
                        <option value="Moto">Moto</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-clock text-primary me-1"></i> Horário Início</label>
                    <input type="time" name="horario_inicio" class="form-control car-form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-clock-history text-primary me-1"></i> Horário Fim</label>
                    <input type="time" name="horario_fim" class="form-control car-form-control" required>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="/anuncio" class="btn-car-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-car-primary">
                    <i class="bi bi-check-circle"></i> Salvar Anúncio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection