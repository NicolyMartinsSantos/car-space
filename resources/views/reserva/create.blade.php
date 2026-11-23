@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 700px;">
    @if(isset($anuncio) && $anuncio)
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="fw-bold h2 mb-1 text-dark">Solicitar Reserva</h1>
                <p class="text-muted mb-0 small">Preencha as informações para agendar sua vaga</p>
            </div>
            <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="car-card p-4 p-md-5">
            <form action="/reserva/store" method="POST">
                @csrf

                <!-- Resumo da Garagem Selecionada -->
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small text-uppercase fw-bold">
                            <i class="bi bi-p-square text-primary me-1"></i> Garagem Selecionada
                        </span>
                        <span class="fw-bold text-primary fs-6">
                            R$ {{ number_format((float)$anuncio->preco, 2, ',', '.') }}
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $anuncio->titulo }}</h5>
                    <p class="text-secondary small mb-2">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $anuncio->endereco }}
                    </p>
                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-car-front text-primary me-1"></i> {{ $anuncio->quantidade_vagas }} vaga(s)</span>
                        <span><i class="bi bi-clock text-primary me-1"></i> {{ substr($anuncio->horario_inicio, 0, 5) }} às {{ substr($anuncio->horario_fim, 0, 5) }}</span>
                    </div>
                    <input type="hidden" name="anuncio_id" value="{{ $anuncio->id }}">
                    @error('anuncio_id')
                        <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
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
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <span class="fw-bold text-dark small">
                            <i class="bi bi-calendar-week-fill text-primary me-1"></i> Dias disponíveis para reserva
                        </span>
                        <span class="text-muted small">
                            Dias em que a garagem aceita agendamentos
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2 pt-1">
                        @foreach($diasSemana as $chave => $nome)
                            @if(in_array($chave, $diasAtivos))
                                <span class="badge rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm"
                                      style="background-color: var(--car-blue-100, #e0f2fe); color: var(--car-blue-800, #075985); border: 1px solid #bae6fd;">
                                    <i class="bi bi-check-circle-fill text-primary"></i> {{ $nome }}
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 fw-normal d-inline-flex align-items-center gap-1 bg-white border text-secondary opacity-50"
                                      style="text-decoration: line-through;">
                                    <i class="bi bi-x-circle"></i> {{ $nome }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-calendar3 text-primary me-1"></i> Data da Reserva</label>
                        <input type="text" 
                               name="data" 
                               id="data_reserva"
                               value="{{ old('data') }}" 
                               placeholder="Selecione a data (DD/MM/AAAA)..."
                               data-dias-permitidos='@json($diasAtivos)'
                               class="form-control car-form-control bg-white" 
                               required>
                        <div id="alerta-dia-invalido" class="alert alert-danger small mt-2 py-2 px-3 d-none" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <span id="mensagem-dia-invalido">Esta garagem não funciona neste dia. Consulte os dias disponíveis acima.</span>
                        </div>
                        @error('data')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
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
                        <label class="car-form-label"><i class="bi bi-clock text-primary me-1"></i> Horário da Reserva</label>
                        <select name="horario" class="form-select car-form-select" required>
                            <option value="">Selecione o horário...</option>
                            @foreach($horariosReserva as $h)
                                <option value="{{ $h }}" {{ old('horario') == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                        @error('horario')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="car-form-label"><i class="bi bi-car-front text-primary me-1"></i> Selecione seu Veículo</label>
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
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    @else
                        <div class="alert alert-warning small mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Você ainda não possui nenhum veículo cadastrado.
                            <a href="/veiculo/create" class="alert-link ms-1">Cadastrar veículo agora</a>.
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="/anuncio/{{ $anuncio->id }}" class="btn-car-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-car-primary" {{ count($veiculos) == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle"></i> Confirmar Reserva
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="fw-bold h2 mb-1 text-dark">Solicitar Reserva</h1>
                <p class="text-muted mb-0 small">Escolha uma vaga disponível na plataforma</p>
            </div>
            <a href="/" class="btn-car-secondary">
                <i class="bi bi-arrow-left"></i> Voltar ao Início
            </a>
        </div>

        <div class="car-card p-5 text-center">
            <i class="bi bi-p-square text-primary display-4 mb-3 d-block"></i>
            <h4 class="fw-bold text-dark">Selecione uma garagem</h4>
            <p class="text-secondary mb-4">Para solicitar uma reserva, primeiro escolha uma das garagens disponíveis na página inicial.</p>
            <a href="/" class="btn-car-primary">
                <i class="bi bi-grid-fill"></i> Ver Garagens Disponíveis
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

        // Separação segura dos componentes [ano, mes, dia] para evitar distorção de fuso horário no Brasil
        const partes = valor.split('-');
        if (partes.length !== 3) return;

        const ano = parseInt(partes[0], 10);
        const mes = parseInt(partes[1], 10) - 1; // 0-indexed no JS
        const dia = parseInt(partes[2], 10);

        const dataLocal = new Date(ano, mes, dia);
        const diaSemanaIndex = dataLocal.getDay();
        const siglaDia = mapaDias[diaSemanaIndex];
        const nomeDia = nomesDias[siglaDia] || 'este dia';

        if (Array.isArray(diasPermitidos) && diasPermitidos.length > 0 && !diasPermitidos.includes(siglaDia)) {
            // Dia não permitido: limpa a seleção e apresenta mensagem clara
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
            // Dia permitido: oculta mensagem de aviso
            if (alertaErro) {
                alertaErro.classList.add('d-none');
            }
        }
    }

    // Inicialização do Flatpickr com pt-BR, altInput DD/MM/AAAA e bloqueio de dias
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
        // Fallback seguro caso o CDN não esteja carregado
        inputData.type = 'date';
        inputData.min = new Date().toISOString().split('T')[0];
        inputData.addEventListener('change', function () {
            validarData(inputData.value);
        });
    }

    // Se houver data pré-preenchida por old('data'), valida na inicialização
    if (inputData.value) {
        validarData(inputData.value);
    }
});
</script>
@endsection