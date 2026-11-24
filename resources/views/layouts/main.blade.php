<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Car Space — Compartilhamento e Reserva de Vagas de Garagem</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Flatpickr (Calendário pt-BR) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Design System Car Space & Alpine.js via Vite -->
    @vite(['resources/css/car-space.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Navbar Principal -->
    <nav class="car-navbar navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="car-brand" href="/" aria-label="Car Space - Página Inicial">
                <img src="{{ asset('images/logo-carspace-light.png') }}" alt="Car Space" class="car-brand-logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#carNavbar" aria-controls="carNavbar" aria-expanded="false" aria-label="Abrir menu de navegação">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="carNavbar">
                <ul class="navbar-nav mx-lg-auto mb-3 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a href="/" class="car-nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="bi bi-compass"></i> Explorar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/anuncio" class="car-nav-link {{ request()->is('anuncio*') ? 'active' : '' }}">
                            <i class="bi bi-megaphone"></i> Anúncios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/veiculo" class="car-nav-link {{ request()->is('veiculo*') ? 'active' : '' }}">
                            <i class="bi bi-car-front"></i> Veículos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/reserva" class="car-nav-link {{ request()->is('reserva*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i> Reservas
                        </a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 pb-2 pb-lg-0">
                    @guest
                        <a href="/login" class="btn-car-secondary btn-car-sm btn-car-pill">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </a>
                        <a href="/register" class="btn-car-primary btn-car-sm btn-car-pill">
                            <i class="bi bi-person-plus"></i> Criar Conta
                        </a>
                    @endguest

                    @auth
                        @php
                            $nomePartes = preg_split('/\s+/', trim(Auth::user()->name));
                            $iniciais = mb_strtoupper(
                                mb_substr($nomePartes[0] ?? 'U', 0, 1) .
                                (count($nomePartes) > 1 ? mb_substr(end($nomePartes), 0, 1) : '')
                            );
                            $primeiroNome = $nomePartes[0] ?? Auth::user()->name;
                        @endphp

                        <a href="/anuncio/create" class="btn-car-secondary btn-car-sm btn-car-pill">
                            <i class="bi bi-plus-circle cs-text-teal"></i> Anunciar Vaga
                        </a>

                        <div class="dropdown">
                            <button class="cs-user-pill dropdown-toggle w-100 justify-content-between justify-content-lg-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span class="cs-avatar">{{ $iniciais }}</span>
                                    <span>{{ $primeiroNome }}</span>
                                </span>
                                <i class="bi bi-chevron-down small text-secondary ms-1"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end cs-dropdown-menu">
                                <li class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-bold small cs-text-navy text-truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-muted text-truncate" style="font-size: 0.78rem;">{{ Auth::user()->email }}</div>
                                </li>
                                <li>
                                    <a class="dropdown-item cs-dropdown-item" href="/profile">
                                        <i class="bi bi-person-gear"></i> Meu Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item cs-dropdown-item" href="/anuncio">
                                        <i class="bi bi-megaphone"></i> Meus Anúncios
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item cs-dropdown-item" href="/veiculo">
                                        <i class="bi bi-car-front"></i> Meus Veículos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item cs-dropdown-item" href="/reserva">
                                        <i class="bi bi-calendar-check"></i> Minhas Reservas
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="/logout" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item cs-dropdown-item cs-dropdown-item-danger w-100">
                                            <i class="bi bi-box-arrow-right"></i> Sair da Conta
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo da Página -->
    <main class="py-4 py-md-5 flex-grow-1">
        @yield('content')
    </main>

    <!-- Rodapé Profissional -->
    <footer class="cs-footer">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-5">
                    <a href="/" class="d-inline-block mb-3" aria-label="Car Space">
                        <img src="{{ asset('images/logo-carspace-dark.jpg') }}" alt="Car Space" class="cs-footer-logo">
                    </a>
                    <p class="small mb-0" style="max-width: 380px; line-height: 1.65;">
                        Plataforma inteligente para compartilhar, anunciar e reservar vagas de garagem com segurança, praticidade e transparência.
                    </p>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="cs-footer-title">Plataforma</div>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                        <li><a href="/" class="cs-footer-link">Explorar garagens</a></li>
                        <li><a href="/anuncio" class="cs-footer-link">Meus anúncios</a></li>
                        <li><a href="/veiculo" class="cs-footer-link">Meus veículos</a></li>
                        <li><a href="/reserva" class="cs-footer-link">Minhas reservas</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-4 col-lg-4">
                    <div class="cs-footer-title">Conta</div>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                        @guest
                            <li><a href="/login" class="cs-footer-link">Entrar na conta</a></li>
                            <li><a href="/register" class="cs-footer-link">Criar nova conta</a></li>
                        @endguest
                        @auth
                            <li><a href="/profile" class="cs-footer-link">Configurações do perfil</a></li>
                            <li><a href="/anuncio/create" class="cs-footer-link">Anunciar nova vaga</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="cs-footer-bottom d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span>&copy; {{ date('Y') }} Car Space. Todos os direitos reservados.</span>
                <span>Gestão e Reserva de Vagas de Garagem</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flatpickr JS & Localização pt-BR -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
</body>
</html>
