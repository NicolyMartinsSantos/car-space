@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Meus Veículos</h1>
            <p class="text-muted mb-0 small">Cadastre e gerencie seus carros e motos</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/veiculo/create" class="btn-car-primary">
                <i class="bi bi-plus-lg"></i> Novo Veículo
            </a>
        </div>
    </div>

    @if(count($data) > 0)
        <div class="car-table-container">
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
                            <td class="fw-bold text-muted">#{{ $veiculo->id }}</td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-car-front text-primary me-2"></i>{{ $veiculo->modelo }}
                            </td>
                            <td><span class="font-monospace bg-light border px-2 py-1 rounded fw-bold text-dark">{{ $veiculo->placa }}</span></td>
                            <td>{{ $veiculo->cor }}</td>
                            <td>{{ $veiculo->ano }}</td>
                            <td><span class="car-badge car-badge-blue">{{ $veiculo->categoria }}</span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/veiculo/edit/{{ $veiculo->id }}" class="btn-car-outline-primary py-1 px-3">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="/veiculo/delete/{{ $veiculo->id }}" class="btn-car-danger py-1 px-3" onclick="return confirm('Tem certeza que deseja excluir este veículo?')">
                                        <i class="bi bi-trash"></i> Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="car-card p-5 text-center">
            <i class="bi bi-car-front text-muted display-4 mb-3 d-block"></i>
            <h4 class="fw-bold text-dark">Nenhum veículo cadastrado</h4>
            <p class="text-secondary mb-4">Adicione seus veículos para poder efetuar reservas de vagas.</p>
            <a href="/veiculo/create" class="btn-car-primary">
                <i class="bi bi-plus-lg"></i> Cadastrar Primeiro Veículo
            </a>
        </div>
    @endif
</div>
@endsection