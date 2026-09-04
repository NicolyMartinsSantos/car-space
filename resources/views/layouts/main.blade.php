<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CAR SPACE - Gestão de Vagas e Reservas</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --car-blue-50: #f0f9ff;
            --car-blue-100: #e0f2fe;
            --car-blue-500: #0ea5e9;
            --car-blue-600: #0284c7;
            --car-blue-700: #0369a1;
            --car-blue-800: #075985;
            --car-dark: #0f172a;
            --car-bg: #f8fafc;
            --car-card-bg: #ffffff;
            --car-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--car-bg);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Customizada */
        .car-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }

        .car-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--car-blue-700);
            text-decoration: none;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .car-brand:hover {
            color: var(--car-blue-800);
        }

        .car-nav-link {
            font-weight: 600;
            color: #475569;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .car-nav-link:hover, .car-nav-link.active {
            color: var(--car-blue-700);
            background-color: var(--car-blue-50);
        }

        /* Card System */
        .car-card {
            background: #ffffff;
            border: 1px solid var(--car-border);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .car-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(2, 132, 199, 0.08);
            border-color: #cbd5e1;
        }

        .car-card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        .car-card-body {
            padding: 1.5rem;
        }

        /* Foto Placeholder Estilizada */
        .car-image-placeholder {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border-radius: 12px;
            height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--car-blue-700);
            position: relative;
            overflow: hidden;
        }

        .car-image-placeholder i {
            font-size: 3rem;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .car-image-placeholder span {
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Badges */
        .car-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.3px;
        }

        .car-badge-blue {
            background-color: var(--car-blue-100);
            color: var(--car-blue-800);
        }

        .car-badge-green {
            background-color: #dcfce7;
            color: #166534;
        }

        .car-badge-amber {
            background-color: #fef3c7;
            color: #92400e;
        }

        .car-badge-gray {
            background-color: #f1f5f9;
            color: #475569;
        }

        /* Botões Padronizados */
        .btn-car-primary {
            background-color: var(--car-blue-600);
            color: #ffffff;
            font-weight: 600;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-car-primary:hover {
            background-color: var(--car-blue-700);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
        }

        .btn-car-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-car-secondary:hover {
            background-color: #f8fafc;
            color: #1e293b;
            border-color: #94a3b8;
        }

        .btn-car-danger {
            background-color: #ef4444;
            color: #ffffff;
            font-weight: 600;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-car-danger:hover {
            background-color: #dc2626;
            color: #ffffff;
        }

        .btn-car-outline-primary {
            background-color: transparent;
            color: var(--car-blue-600);
            border: 1px solid var(--car-blue-600);
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-car-outline-primary:hover {
            background-color: var(--car-blue-50);
            color: var(--car-blue-700);
        }

        /* Formulários */
        .car-form-control, .car-form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            color: #1e293b;
            background-color: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .car-form-control:focus, .car-form-select:focus {
            border-color: var(--car-blue-500);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
            outline: none;
        }

        .car-form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        /* Tabelas */
        .car-table-container {
            background: #ffffff;
            border: 1px solid var(--car-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .car-table {
            margin-bottom: 0;
            width: 100%;
        }

        .car-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .car-table td {
            padding: 16px 20px;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .car-table tr:last-child td {
            border-bottom: none;
        }

        /* Footer */
        footer {
            margin-top: auto;
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 24px 0;
            color: #64748b;
            font-size: 0.9rem;
        }

        .hero-section {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            padding: 40px 0;
            border-radius: 20px;
            margin-bottom: 32px;
            box-shadow: 0 10px 25px rgba(2, 132, 199, 0.15);
        }
    </style>
</head>
<body>

    <!-- Navbar Principal -->
    <nav class="car-navbar navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="car-brand" href="/">
                <i class="bi bi-p-square-fill text-primary fs-3"></i>
                <span>CAR SPACE</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#carNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-2 mt-lg-0" id="carNavbar">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a href="/" class="car-nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/anuncio" class="car-nav-link {{ request()->is('anuncio*') ? 'active' : '' }}">
                            <i class="bi bi-mega-phone"></i> Anúncios
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

                <div class="d-flex align-items-center gap-2">
                    @guest
                        <a href="/login" class="btn-car-secondary px-3 py-2 text-decoration-none">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </a>
                        <a href="/register" class="btn-car-primary px-3 py-2 text-decoration-none">
                            <i class="bi bi-person-plus"></i> Criar Conta
                        </a>
                    @endguest

                    @auth
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-secondary small">
                                <i class="bi bi-person-circle text-primary me-1"></i> Olá, <strong class="text-dark">{{ Auth::user()->name }}</strong>
                            </span>
                            <a href="/profile" class="btn-car-secondary px-3 py-2 text-decoration-none small">
                                <i class="bi bi-gear"></i> Perfil
                            </a>
                            <form action="/logout" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn-car-danger px-3 py-2 border-0 small">
                                    <i class="bi bi-box-arrow-right"></i> Sair
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo da Página -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Rodapé -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">
                <i class="bi bi-p-square-fill me-1 text-primary"></i> <strong>CAR SPACE</strong> &copy; {{ date('Y') }} — Sistema de Gestão e Reserva de Vagas de Garagem.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
