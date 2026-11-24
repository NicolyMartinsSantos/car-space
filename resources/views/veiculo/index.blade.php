@extends('layouts.main')

@section('content')
<div class="container">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Meus Veículos</h1>
            <p class="cs-page-subtitle">Cadastre e gerencie os carros e motos vinculados à sua conta</p>
        </div>
        <a href="/veiculo/create" class="btn-car-primary btn-car-pill">
            <i class="bi bi-plus-lg"></i> Novo Veículo
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
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Cor</th>
                            <th>Ano</th>
                            <th>Categoria</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $veiculo)
                            <tr>
                                <td class="fw-bold text-muted cs-tabular">#{{ $veiculo->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-3"
                                              style="width: 34px; height: 34px; background: var(--cs-teal-100); color: var(--cs-teal-700);">
                                            <i class="bi {{ strtolower($veiculo->categoria) === 'moto' ? 'bi-bicycle' : 'bi-car-front-fill' }}"></i>
                                        </span>
                                        <span class="fw-bold cs-text-navy">{{ $veiculo->modelo }}</span>
                                    </div>
                                </td>
                                <td><span class="cs-plate">{{ $veiculo->placa }}</span></td>
                                <td class="text-secondary">{{ $veiculo->cor }}</td>
                                <td class="cs-tabular fw-medium">{{ $veiculo->ano }}</td>
                                <td><span class="car-badge car-badge-navy">{{ $veiculo->categoria }}</span></td>
                                <td class="text-end">
                                    <div class="d-inline-flex justify-content-end gap-2">
                                        <a href="/veiculo/edit/{{ $veiculo->id }}" class="btn-car-outline-primary btn-car-sm">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="/veiculo/delete/{{ $veiculo->id }}" class="btn-car-danger-soft" onclick="return confirm('Tem certeza que deseja excluir este veículo?')">
                                            <i class="bi bi-trash"></i> Excluir
                                        </a>
                                    </div>
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
                <i class="bi bi-car-front"></i>
            </div>
            <h3 class="h4 fw-bold mb-2">Nenhum veículo cadastrado</h3>
            <p class="text-secondary mb-4">Adicione seus veículos para solicitar reservas de garagem com rapidez.</p>
            <a href="/veiculo/create" class="btn-car-primary btn-car-pill">
                <i class="bi bi-plus-lg"></i> Cadastrar Primeiro Veículo
            </a>
        </div>
    @endif
</div>
@endsection
