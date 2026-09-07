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