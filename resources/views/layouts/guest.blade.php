<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Car Space — Acesso à Plataforma</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5.3 & Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Vite Assets & Design System Car Space -->
        @vite(['resources/css/app.css', 'resources/css/car-space.css', 'resources/js/app.js'])
    </head>
    <body class="p-0 m-0">
        <div class="cs-auth-page">
            <!-- Painel Institucional Escuro (Desktop) -->
            <aside class="cs-auth-aside">
                <div>
                    <a href="/" class="d-inline-block mb-4" aria-label="Car Space">
                        <img src="{{ asset('images/logo-carspace-dark.jpg') }}" alt="Car Space" class="cs-auth-logo-dark">
                    </a>

                    <h2 class="text-white fw-bold h3 mb-3" style="max-width: 390px; line-height: 1.3;">
                        Estacionar ou anunciar sua vaga nunca foi tão simples.
                    </h2>
                    <p class="mb-4" style="color: rgba(255, 255, 255, 0.72); max-width: 380px; font-size: 0.95rem; line-height: 1.65;">
                        Conectamos motoristas a garagens disponíveis com transparência de preços, horários e dias de atendimento.
                    </p>

                    <div class="d-flex flex-column gap-3 mt-4" style="max-width: 400px;">
                        <div class="cs-auth-feature">
                            <span class="cs-auth-feature-icon"><i class="bi bi-shield-check"></i></span>
                            <div>
                                <div class="fw-semibold text-white small">Reservas organizadas</div>
                                <div style="color: rgba(255, 255, 255, 0.65); font-size: 0.82rem;">Acompanhe aprovações, datas e veículos em um só lugar.</div>
                            </div>
                        </div>
                        <div class="cs-auth-feature">
                            <span class="cs-auth-feature-icon"><i class="bi bi-calendar-week"></i></span>
                            <div>
                                <div class="fw-semibold text-white small">Disponibilidade clara</div>
                                <div style="color: rgba(255, 255, 255, 0.65); font-size: 0.82rem;">Consulte horários e dias da semana antes de solicitar sua vaga.</div>
                            </div>
                        </div>
                        <div class="cs-auth-feature">
                            <span class="cs-auth-feature-icon"><i class="bi bi-house-check"></i></span>
                            <div>
                                <div class="fw-semibold text-white small">Rentabilize sua garagem</div>
                                <div style="color: rgba(255, 255, 255, 0.65); font-size: 0.82rem;">Publique seu anúncio e gerencie solicitações com autonomia.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important; color: rgba(255,255,255,0.5); font-size: 0.82rem;">
                    &copy; {{ date('Y') }} Car Space — Gestão e Reserva de Vagas de Garagem
                </div>
            </aside>

            <!-- Painel do Formulário -->
            <main class="cs-auth-main">
                <!-- Logo para Mobile / Tablet -->
                <div class="text-center mb-4 d-lg-none">
                    <a href="/" class="d-inline-block" aria-label="Car Space">
                        <img src="{{ asset('images/logo-carspace-light.png') }}" alt="Car Space" style="height: 58px; width: auto;">
                    </a>
                </div>

                <div class="cs-auth-card">
                    {{ $slot }}
                </div>

                <div class="text-center mt-4">
                    <a href="/" class="text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1" style="color: var(--cs-text-secondary);">
                        <i class="bi bi-arrow-left"></i> Voltar para a página inicial
                    </a>
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
