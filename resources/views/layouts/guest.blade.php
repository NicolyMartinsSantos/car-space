<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CAR SPACE - Autenticação</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5.3 & Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                --car-border: #e2e8f0;
            }

            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background-color: var(--car-bg);
                color: #334155;
                min-height: 100vh;
            }

            .car-brand {
                font-weight: 800;
                color: var(--car-blue-700);
                letter-spacing: -0.5px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .car-card {
                background: #ffffff;
                border: 1px solid var(--car-border);
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            }

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
            }

            .car-form-control {
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                padding: 10px 14px;
                font-size: 0.95rem;
                color: #1e293b;
                background-color: #ffffff;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .car-form-control:focus {
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
        </style>
    </head>
    <body class="d-flex align-items-center justify-content-center py-5">
        <div class="container" style="max-width: 440px;">
            <div class="text-center mb-4">
                <a href="/" class="car-brand fs-2 text-decoration-none d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-p-square-fill text-primary"></i>
                    <span>CAR SPACE</span>
                </a>
                <p class="text-muted small mb-0 mt-1">Gestão e Reserva de Vagas de Garagem</p>
            </div>

            <div class="car-card p-4 p-sm-5">
                {{ $slot }}
            </div>

            <div class="text-center mt-4">
                <a href="/" class="text-secondary small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Voltar para a página inicial
                </a>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
