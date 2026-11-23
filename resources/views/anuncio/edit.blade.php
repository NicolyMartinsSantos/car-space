@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1 text-dark">Editar Anúncio</h1>
            <p class="text-muted mb-0 small">Atualize as informações do seu anúncio #{{ $anuncio->id }}</p>
        </div>
        <a href="/anuncio" class="btn-car-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/anuncio/update/{{ $anuncio->id }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="car-form-label"><i class="bi bi-fonts text-primary me-1"></i> Título do Anúncio</label>
                <input type="text" name="titulo" value="{{ $anuncio->titulo }}" class="form-control car-form-control" required>
            </div>

            <div class="mb-4">
                <label class="car-form-label"><i class="bi bi-card-text text-primary me-1"></i> Descrição Detalhada</label>
                <textarea name="descricao" rows="4" class="form-control car-form-control" required>{{ $anuncio->descricao }}</textarea>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="car-form-label"><i class="bi bi-geo-alt text-primary me-1"></i> Endereço Completo</label>
                    <input type="text" name="endereco" value="{{ $anuncio->endereco }}" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-currency-dollar text-primary me-1"></i> Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" value="{{ $anuncio->preco }}" class="form-control car-form-control" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-telephone text-primary me-1"></i> Telefone / WhatsApp</label>
                    <input type="text" name="telefone" value="{{ $anuncio->telefone }}" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-car-front text-primary me-1"></i> Quantidade de Vagas</label>
                    <input type="number" name="quantidade_vagas" value="{{ $anuncio->quantidade_vagas }}" class="form-control car-form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="car-form-label"><i class="bi bi-tag text-primary me-1"></i> Categoria da Vaga</label>
                    <select name="categoria_vaga" class="form-select car-form-select" required>
                        <option value="Carro" {{ $anuncio->categoria_vaga == 'Carro' ? 'selected' : '' }}>Carro</option>
                        <option value="Moto" {{ $anuncio->categoria_vaga == 'Moto' ? 'selected' : '' }}>Moto</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="car-form-label d-block">
                    <i class="bi bi-calendar-week text-primary me-1"></i> Dias da Semana Disponíveis
                </label>
                <div class="d-flex flex-wrap gap-2 pt-1">
                    @php
                        $diasSemana = [
                            'seg' => 'Segunda-feira',
                            'ter' => 'Terça-feira',
                            'qua' => 'Quarta-feira',
                            'qui' => 'Quinta-feira',
                            'sex' => 'Sexta-feira',
                            'sab' => 'Sábado',
                            'dom' => 'Domingo'
                        ];
                        $diasSelecionados = old('dias_disponiveis', $anuncio->dias_disponiveis ?? []);
                    @endphp

                    @foreach($diasSemana as $chave => $nome)
                        <input type="checkbox" 
                               class="btn-check" 
                               name="dias_disponiveis[]" 
                               value="{{ $chave }}" 
                               id="edit_dia_{{ $chave }}" 
                               autocomplete="off"
                               {{ in_array($chave, (array)$diasSelecionados) ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold" for="edit_dia_{{ $chave }}">
                            {{ $nome }}
                        </label>
                    @endforeach
                </div>
                <div class="form-text text-muted small mt-1">
                    <i class="bi bi-info-circle me-1"></i> Selecione os dias em que a vaga estará aberta para receber veículos.
                </div>
                @error('dias_disponiveis')
                    <div class="text-danger small mt-2">
                        <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            @php
                $horariosPadrao = [];
                for ($h = 0; $h < 24; $h++) {
                    foreach ([0, 30] as $m) {
                        $horariosPadrao[] = sprintf('%02d:%02d', $h, $m);
                    }
                }

                $horarioInicioSalvo = $anuncio->horario_inicio ? substr($anuncio->horario_inicio, 0, 5) : null;
                $horarioFimSalvo = $anuncio->horario_fim ? substr($anuncio->horario_fim, 0, 5) : null;

                $horarioInicioSelecionado = old('horario_inicio', $horarioInicioSalvo);
                $horarioFimSelecionado = old('horario_fim', $horarioFimSalvo);

                // Monta opções para Horário Início preservando horários antigos com minutos específicos (ex: 07:45)
                $opcoesInicio = $horariosPadrao;
                if ($horarioInicioSalvo && !in_array($horarioInicioSalvo, $opcoesInicio)) {
                    $opcoesInicio[] = $horarioInicioSalvo;
                    sort($opcoesInicio);
                }
                if ($horarioInicioSelecionado && !in_array($horarioInicioSelecionado, $opcoesInicio)) {
                    $opcoesInicio[] = $horarioInicioSelecionado;
                    sort($opcoesInicio);
                }

                // Monta opções para Horário Fim preservando horários antigos com minutos específicos (ex: 12:35)
                $opcoesFim = $horariosPadrao;
                if ($horarioFimSalvo && !in_array($horarioFimSalvo, $opcoesFim)) {
                    $opcoesFim[] = $horarioFimSalvo;
                    sort($opcoesFim);
                }
                if ($horarioFimSelecionado && !in_array($horarioFimSelecionado, $opcoesFim)) {
                    $opcoesFim[] = $horarioFimSelecionado;
                    sort($opcoesFim);
                }
            @endphp

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-clock text-primary me-1"></i> Horário Início (Abertura)</label>
                    <select name="horario_inicio" class="form-select car-form-select" required>
                        <option value="">Selecione o horário...</option>
                        @foreach($opcoesInicio as $h)
                            <option value="{{ $h }}" {{ $horarioInicioSelecionado == $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                    @error('horario_inicio')
                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="car-form-label"><i class="bi bi-clock-history text-primary me-1"></i> Horário Fim (Fechamento)</label>
                    <select name="horario_fim" class="form-select car-form-select" required>
                        <option value="">Selecione o horário...</option>
                        @foreach($opcoesFim as $h)
                            <option value="{{ $h }}" {{ $horarioFimSelecionado == $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                    @error('horario_fim')
                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="/anuncio" class="btn-car-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-car-primary">
                    <i class="bi bi-check-circle"></i> Atualizar Anúncio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection