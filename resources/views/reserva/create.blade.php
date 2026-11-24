@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 780px;">
    @if(isset($anuncio) && $anuncio)
        <div class="cs-page-header">
            <div>
                <h1 class="cs-page-title">Solicitar Reserva</h1>
                <p class="cs-page-subtitle">Escolha a data, o horário e o veículo para agendar sua vaga</p>
            </div>
            <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary btn-car-sm btn-car-pill">
                <i class="bi bi-arrow-left"></i> Voltar ao Anúncio
            </a>
        </div>

        <div class="car-card p-4 p-md-5">
            <form action="/reserva/store" method="POST">
                @csrf

                <!-- Resumo da Garagem Selecionada -->
                <div class="p-4 rounded-4 border mb-4" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                    <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
                        <x-cs-listing-cover :anuncio="$anuncio" size="thumb" />
                        <div class="flex-grow-1 w-100">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                <span class="car-badge car-badge-teal">
                                    <i class="bi bi-p-square-fill"></i> Garagem Selecionada
                                </span>
                                <span class="cs-price-value fs-5">
                                    R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                                </span>
                            </div>
                            <h2 class="h5 fw-bold mb-1">{{ $anuncio->titulo }}</h2>
                            <p class="cs-listing-address mb-2">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>{{ $anuncio->endereco }}</span>
                            </p>
                            <div class="d-flex flex-wrap gap-3 text-secondary small">
                                <span><i class="bi bi-car-front cs-text-teal me-1"></i> {{ $anuncio->quantidade_vagas }} {{ (int)$anuncio->quantidade_vagas === 1 ? 'vaga' : 'vagas' }}</span>
                                <span class="cs-tabular"><i class="bi bi-clock cs-text-teal me-1"></i> {{ substr($anuncio->horario_inicio, 0, 5) }} às {{ substr($anuncio->horario_fim, 0, 5) }}</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="anuncio_id" value="{{ $anuncio->id }}">
                    @error('anuncio_id')
                        <div class="small mt-3 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $diasSemana = [
                        'seg' => 'Segunda',
                        'ter' => 'Terça',
                        'qua' => 'Quarta',
                        'qui' => 'Quinta',
                        'sex' => 'Sexta',
                        'sab' => 'Sábado',
                        'dom' => 'Domingo'
                    ];
                    $diasAtivos = (array)($anuncio->dias_disponiveis ?? []);
                @endphp

                <!-- Dias disponíveis para reserva -->
                <div class="p-3 p-md-4 rounded-4 border mb-4" style="background: var(--cs-bg); border-color: var(--cs-border) !important;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <span class="fw-bold cs-text-navy small d-inline-flex align-items-center gap-2">
                            <i class="bi bi-calendar-week-fill cs-text-teal"></i> Dias disponíveis para reserva
                        </span>
                        <span class="text-muted small">
                            Dias em que a garagem aceita agendamentos
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($diasSemana as $chave => $nome)
                            @if(in_array($chave, $diasAtivos))
                                <span class="cs-day-chip-active">
                                    <i class="bi bi-check-circle-fill"></i> {{ $nome }}
                                </span>
                            @else
                                <span class="cs-day-chip-inactive">
                                    <i class="bi bi-x-circle"></i> {{ $nome }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Data e Horário -->
                <div class="cs-form-section">
                    <div class="cs-form-section-title">
                        <i class="bi bi-calendar-event"></i> Data e Horário
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="car-form-label"><i class="bi bi-calendar3"></i> Data da Reserva</label>
                            <input type="text"
                                   name="data"
                                   id="data_reserva"
                                   value="{{ old('data') }}"
                                   placeholder="Selecione a data (DD/MM/AAAA)..."
                                   data-dias-permitidos='@json($diasAtivos)'
                                   class="form-control car-form-control bg-white"
                                   required>
                            <div id="alerta-dia-invalido" class="cs-alert cs-alert-danger small mt-2 py-2 px-3 d-none" role="alert">
                                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                                <span id="mensagem-dia-invalido">Esta garagem não funciona neste dia. Consulte os dias disponíveis acima.</span>
                            </div>
                            @error('data')
                                <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        @php
                            $horariosReserva = [];
                            if (isset($anuncio) && $anuncio->horario_inicio && $anuncio->horario_fim) {
                                $iniStr = substr($anuncio->horario_inicio, 0, 5);
                                $fimStr = substr($anuncio->horario_fim, 0, 5);
                                $tsIni = strtotime("1970-01-01 $iniStr:00");
                                $tsFim = strtotime("1970-01-01 $fimStr:00");

                                if ($tsFim < $tsIni) {
                                    // Garagem que funciona virando a noite (ex: 22:00 às 06:00)
                                    $curr = $tsIni;
                                    $limiteNoite = strtotime("1970-01-01 23:59:59");
                                    while ($curr <= $limiteNoite) {
                                        $horariosReserva[] = date('H:i', $curr);
                                        $curr += 1800; // intervalos de 30 min
                                    }
                                    $curr = strtotime("1970-01-02 00:00:00");
                                    $fimSegDia = strtotime("1970-01-02 $fimStr:00");
                                    while ($curr <= $fimSegDia) {
                                        $horariosReserva[] = date('H:i', $curr);
                                        $curr += 1800;
                                    }
                                } elseif ($tsFim == $tsIni) {
                                    // 24 horas
                                    for ($h = 0; $h < 24; $h++) {
                                        $horariosReserva[] = sprintf('%02d:00', $h);
                                        $horariosReserva[] = sprintf('%02d:30', $h);
                                    }
                                } else {
                                    $curr = $tsIni;
                                    while ($curr <= $tsFim) {
                                        $horariosReserva[] = date('H:i', $curr);
                                        $curr += 1800;
                                    }
                                    if (!in_array($fimStr, $horariosReserva)) {
                                        $horariosReserva[] = $fimStr;
                                        sort($horariosReserva);
                                    }
                                }
                            }

                            if (empty($horariosReserva)) {
                                // Fallback para anúncios sem horários definidos
                                for ($h = 6; $h <= 23; $h++) {
                                    $horariosReserva[] = sprintf('%02d:00', $h);
                                    $horariosReserva[] = sprintf('%02d:30', $h);
                                }
                            }

                            $horariosReserva = array_values(array_unique($horariosReserva));

                            // Preserva old('horario') se houver
                            $horarioSelecionado = old('horario');
                            if ($horarioSelecionado) {
                                $horarioSelecionadoFormatado = substr($horarioSelecionado, 0, 5);
                                if (!in_array($horarioSelecionadoFormatado, $horariosReserva)) {
                                    $horariosReserva[] = $horarioSelecionadoFormatado;
                                    sort($horariosReserva);
                                }
                            }
                        @endphp

                        <div class="col-md-6">
                            <label class="car-form-label"><i class="bi bi-clock"></i> Horário da Reserva</label>
                            <select name="horario" class="form-select car-form-select" required>
                                <option value="">Selecione o horário...</option>
                                @foreach($horariosReserva as $h)
                                    <option value="{{ $h }}" {{ old('horario') == $h ? 'selected' : '' }}>
                                        {{ $h }}
                                    </option>
                                @endforeach
                            </select>
                            @error('horario')
                                <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Veículo -->
                <div class="cs-form-section">
                    <div class="cs-form-section-title">
                        <i class="bi bi-car-front-fill"></i> Veículo da Reserva
                    </div>

                    <label class="car-form-label"><i class="bi bi-car-front"></i> Selecione seu Veículo</label>
                    @if(count($veiculos) > 0)
                        <select name="veiculo_id" class="form-select car-form-select" required>
                            <option value="">Selecione o veículo...</option>
                            @foreach($veiculos as $veiculo)
                                <option value="{{ $veiculo->id }}" {{ old('veiculo_id') == $veiculo->id ? 'selected' : '' }}>
                                    {{ $veiculo->modelo }} ({{ $veiculo->placa }})
                                </option>
                            @endforeach
                        </select>
                        @error('veiculo_id')
                            <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    @else
                        <div class="cs-alert cs-alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                            <div>
                                Você ainda não possui nenhum veículo cadastrado.
                                <a href="/veiculo/create" class="fw-bold cs-text-navy ms-1">Cadastrar veículo agora</a>.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top" style="border-color: var(--cs-border) !important;">
                    <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-car-accent" {{ count($veiculos) == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle"></i> Confirmar Reserva
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="cs-page-header">
            <div>
                <h1 class="cs-page-title">Solicitar Reserva</h1>
                <p class="cs-page-subtitle">Escolha uma vaga disponível na plataforma</p>
            </div>
            <a href="/" class="btn-car-secondary btn-car-sm btn-car-pill">
                <i class="bi bi-arrow-left"></i> Voltar ao Início
            </a>
        </div>

        <div class="cs-empty-state">
            <div class="cs-empty-icon">
                <i class="bi bi-p-square"></i>
            </div>
            <h3 class="h4 fw-bold mb-2">Selecione uma garagem</h3>
            <p class="text-secondary mb-4">Para solicitar uma reserva, primeiro escolha uma das garagens disponíveis na página inicial.</p>
            <a href="/" class="btn-car-primary btn-car-pill">
                <i class="bi bi-compass"></i> Ver Garagens Disponíveis
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputData = document.getElementById('data_reserva');
    const alertaErro = document.getElementById('alerta-dia-invalido');
    const mensagemErro = document.getElementById('mensagem-dia-invalido');

    if (!inputData) return;

    let diasPermitidos = [];
    try {
        diasPermitidos = JSON.parse(inputData.getAttribute('data-dias-permitidos') || '[]');
    } catch (e) {
        diasPermitidos = [];
    }

    const mapaDias = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab'];
    const nomesDias = {
        'dom': 'Domingo',
        'seg': 'Segunda-feira',
        'ter': 'Terça-feira',
        'qua': 'Quarta-feira',
        'qui': 'Quinta-feira',
        'sex': 'Sexta-feira',
        'sab': 'Sábado'
    };

    function validarData(valor) {
        if (!valor) {
            valor = inputData.value;
        }
        if (!valor) {
            if (alertaErro) alertaErro.classList.add('d-none');
            return;
        }

        const partes = valor.split('-');
        if (partes.length !== 3) return;

        const ano = parseInt(partes[0], 10);
        const mes = parseInt(partes[1], 10) - 1;
        const dia = parseInt(partes[2], 10);

        const dataLocal = new Date(ano, mes, dia);
        const diaSemanaIndex = dataLocal.getDay();
        const siglaDia = mapaDias[diaSemanaIndex];
        const nomeDia = nomesDias[siglaDia] || 'este dia';

        if (Array.isArray(diasPermitidos) && diasPermitidos.length > 0 && !diasPermitidos.includes(siglaDia)) {
            if (window.fpReserva && typeof window.fpReserva.clear === 'function') {
                window.fpReserva.clear();
            } else {
                inputData.value = '';
            }
            if (alertaErro && mensagemErro) {
                mensagemErro.textContent = 'Esta garagem não funciona aos ' + nomeDia + 's. Consulte os dias disponíveis acima.';
                alertaErro.classList.remove('d-none');
            }
            if (window.fpReserva && window.fpReserva.altInput) {
                window.fpReserva.altInput.focus();
            } else {
                inputData.focus();
            }
        } else {
            if (alertaErro) {
                alertaErro.classList.add('d-none');
            }
        }
    }

    if (typeof flatpickr !== 'undefined') {
        const flatpickrConfig = {
            locale: (typeof flatpickr.l10ns !== 'undefined' && flatpickr.l10ns.pt) ? flatpickr.l10ns.pt : 'pt',
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            altInputClass: "form-control car-form-control bg-white",
            disable: [
                function(date) {
                    if (!diasPermitidos || diasPermitidos.length === 0) return false;
                    const sigla = mapaDias[date.getDay()];
                    return !diasPermitidos.includes(sigla);
                }
            ],
            onChange: function(selectedDates, dateStr) {
                validarData(dateStr);
            }
        };

        window.fpReserva = flatpickr(inputData, flatpickrConfig);
    } else {
        inputData.type = 'date';
        inputData.min = new Date().toISOString().split('T')[0];
        inputData.addEventListener('change', function () {
            validarData(inputData.value);
        });
    }

    if (inputData.value) {
        validarData(inputData.value);
    }
});
</script>
@endsection
