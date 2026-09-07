@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 900px;">
    <div class="mb-4">
        <h1 class="fw-bold h2 mb-1 text-dark">Meu Perfil</h1>
        <p class="text-muted mb-0 small">Gerencie as informações da sua conta e configurações de segurança</p>
    </div>

    <div class="d-flex flex-column gap-4">
        <!-- Informações do Perfil -->
        <div class="car-card p-4 p-md-5">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Atualizar Senha -->
        <div class="car-card p-4 p-md-5">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Excluir Conta -->
        <div class="car-card p-4 p-md-5 border-danger-subtle">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
