@extends('layouts.main')

@section('content')
@php
    $nomePartes = preg_split('/\s+/', trim($user->name));
    $iniciais = mb_strtoupper(
        mb_substr($nomePartes[0] ?? 'U', 0, 1) .
        (count($nomePartes) > 1 ? mb_substr(end($nomePartes), 0, 1) : '')
    );
@endphp

<div class="container" style="max-width: 920px;">
    <!-- Cabeçalho do Perfil -->
    <div class="cs-page-header">
        <div class="d-flex align-items-center gap-3">
            <span class="cs-avatar cs-avatar-lg">{{ $iniciais }}</span>
            <div>
                <h1 class="cs-page-title">Meu Perfil</h1>
                <p class="cs-page-subtitle">{{ $user->name }} &middot; {{ $user->email }}</p>
            </div>
        </div>
        <a href="/" class="btn-car-secondary btn-car-sm btn-car-pill">
            <i class="bi bi-arrow-left"></i> Voltar ao Início
        </a>
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
        <div class="car-card p-4 p-md-5" style="border-color: var(--cs-danger-border);">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
