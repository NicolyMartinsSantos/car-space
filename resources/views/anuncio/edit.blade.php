@extends('layouts.main')

@section('content')
@php
    $fotosAtuais = ($anuncio->fotos ?? collect())->filter(fn ($f) => $f->arquivoExiste())->values();
    $maxFotos = \App\Models\Anuncio::MAX_FOTOS;
@endphp

<div class="container" style="max-width: 840px;">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Editar Anúncio</h1>
            <p class="cs-page-subtitle">Atualize as informações e fotografias do anúncio <strong class="cs-text-navy">#{{ $anuncio->id }}</strong></p>
        </div>
        <a href="/anuncio" class="btn-car-secondary btn-car-sm btn-car-pill">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/anuncio/update/{{ $anuncio->id }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Bloco 1: Informações da Vaga -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-card-heading"></i> Informações Principais
                </div>

                <div class="mb-3">
                    <label class="car-form-label"><i class="bi bi-fonts"></i> Título do Anúncio</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $anuncio->titulo) }}" class="form-control car-form-control" required>
                </div>

                <div>
                    <label class="car-form-label"><i class="bi bi-card-text"></i> Descrição Detalhada</label>
                    <textarea name="descricao" rows="4" class="form-control car-form-control" required>{{ old('descricao', $anuncio->descricao) }}</textarea>
                </div>
            </div>

            <!-- Bloco 2: Gerenciamento de Fotografias -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-images"></i> Fotografias da Garagem
                </div>

                @if($fotosAtuais->count() > 0)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <span class="car-form-label mb-0">
                                <i class="bi bi-collection"></i> Fotos atuais ({{ $fotosAtuais->count() }})
                            </span>
                            <span class="text-muted small">
                                Fotos existentes só são alteradas ou excluídas se você marcar explicitamente.
                            </span>
                        </div>

                        <div class="cs-preview-grid" id="existing_photos_grid">
                            @foreach($fotosAtuais as $index => $foto)
                                @php
                                    $marcadaRemover = in_array($foto->id, (array) old('remover_fotos', []));
                                @endphp
                                <div class="cs-existing-photo-card {{ $marcadaRemover ? 'is-marked-delete' : '' }}" data-photo-card="{{ $foto->id }}">
                                    <div class="cs-preview-thumb-box">
                                        <img src="{{ $foto->url }}"
                                             data-original-src="{{ $foto->url }}"
                                             alt="Foto {{ $index + 1 }} do anúncio"
                                             id="img_existente_{{ $foto->id }}">

                                        @if($index === 0)
                                            <span class="cs-preview-cover-tag">Capa</span>
                                        @endif

                                        <div class="cs-photo-delete-overlay">
                                            <span class="car-badge car-badge-red">Será removida</span>
                                        </div>
                                    </div>

                                    <!-- Checkbox oculto para remoção explícita -->
                                    <input type="checkbox"
                                           name="remover_fotos[]"
                                           value="{{ $foto->id }}"
                                           id="remover_foto_{{ $foto->id }}"
                                           class="d-none js-remover-foto-checkbox"
                                           {{ $marcadaRemover ? 'checked' : '' }}>

                                    <!-- Input oculto para substituição individual -->
                                    <input type="file"
                                           name="substituir_fotos[{{ $foto->id }}]"
                                           id="substituir_foto_{{ $foto->id }}"
                                           accept="image/jpeg,image/png,image/webp"
                                           class="d-none js-substituir-foto-input"
                                           data-photo-id="{{ $foto->id }}">

                                    <div class="p-2 d-flex flex-column gap-1">
                                        <span class="small fw-semibold cs-text-teal-dark d-none" id="status_subst_{{ $foto->id }}" style="font-size: 0.72rem;">
                                            <i class="bi bi-arrow-repeat"></i> Nova imagem selecionada
                                        </span>

                                        <div class="d-flex gap-1">
                                            <button type="button"
                                                    class="btn-car-secondary btn-car-sm flex-grow-1 py-1 px-2 js-btn-substituir"
                                                    data-photo-id="{{ $foto->id }}"
                                                    style="font-size: 0.76rem;">
                                                <i class="bi bi-arrow-repeat"></i> Substituir
                                            </button>

                                            <button type="button"
                                                    class="btn-car-danger-soft py-1 px-2 js-btn-toggle-remover"
                                                    data-photo-id="{{ $foto->id }}"
                                                    style="font-size: 0.76rem;">
                                                <i class="bi {{ $marcadaRemover ? 'bi-arrow-counterclockwise' : 'bi-trash' }}"></i>
                                                <span>{{ $marcadaRemover ? 'Desfazer' : 'Excluir' }}</span>
                                            </button>
                                        </div>

                                        <button type="button"
                                                class="btn btn-link btn-sm text-secondary p-0 text-decoration-none d-none js-btn-desfazer-subst"
                                                data-photo-id="{{ $foto->id }}"
                                                style="font-size: 0.72rem;">
                                            Cancelar substituição
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="cs-alert cs-alert-info small mb-3">
                        <i class="bi bi-info-circle-fill cs-text-teal flex-shrink-0"></i>
                        <span>Este anúncio ainda não possui fotografias cadastradas. Você pode adicionar imagens abaixo.</span>
                    </div>
                @endif

                <label for="input_fotos_edit" id="dropzone_fotos_edit" class="cs-upload-dropzone mb-3">
                    <span class="cs-upload-icon">
                        <i class="bi bi-plus-circle"></i>
                    </span>
                    <div class="fw-bold cs-text-navy mb-1">
                        Adicionar novas fotografias
                    </div>
                    <div class="text-secondary small">
                        Limite total de <strong>{{ $maxFotos }} fotos</strong> por anúncio &middot; JPG, PNG ou WEBP (máx. 5 MB por foto)
                    </div>
                    <input
                        type="file"
                        id="input_fotos_edit"
                        name="fotos[]"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        class="d-none"
                    >
                </label>

                <div id="alerta_fotos_edit" class="cs-alert cs-alert-warning small mb-3 d-none" role="alert">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                    <span id="alerta_fotos_edit_msg"></span>
                </div>

                @error('fotos')
                    <div class="small mb-2 fw-medium" style="color: var(--cs-danger-text);">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                    </div>
                @enderror
                @foreach($errors->get('fotos.*') as $mensagensFoto)
                    @foreach($mensagensFoto as $mensagemFoto)
                        <div class="small mb-2 fw-medium" style="color: var(--cs-danger-text);">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $mensagemFoto }}
                        </div>
                    @endforeach
                @endforeach
                @foreach($errors->get('substituir_fotos.*') as $mensagensSub)
                    @foreach($mensagensSub as $mensagemSub)
                        <div class="small mb-2 fw-medium" style="color: var(--cs-danger-text);">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $mensagemSub }}
                        </div>
                    @endforeach
                @endforeach

                <div id="preview_fotos_edit" class="cs-preview-grid d-none"></div>
            </div>

            <!-- Bloco 3: Localização, Preço e Características -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-geo-alt-fill"></i> Localização, Valores e Capacidade
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="car-form-label"><i class="bi bi-geo-alt"></i> Endereço Completo</label>
                        <input type="text" name="endereco" value="{{ old('endereco', $anuncio->endereco) }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-building"></i> Cidade <span class="text-muted fw-normal small">(opcional)</span></label>
                        <input type="text" name="cidade" value="{{ old('cidade', $anuncio->cidade) }}" maxlength="100" placeholder="Ex: Matinhos, Curitiba..." class="form-control car-form-control">
                        @if(empty($anuncio->cidade))
                            <div class="form-text text-muted small mt-1">Informe a cidade para que este anúncio apareça na busca por cidade.</div>
                        @endif
                        @error('cidade')
                            <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="car-form-label"><i class="bi bi-currency-dollar"></i> Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" value="{{ old('preco', $anuncio->preco) }}" class="form-control car-form-control" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-telephone"></i> Telefone / WhatsApp</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $anuncio->telefone) }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-car-front"></i> Quantidade de Vagas</label>
                        <input type="number" name="quantidade_vagas" value="{{ old('quantidade_vagas', $anuncio->quantidade_vagas) }}" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-tag"></i> Categoria da Vaga</label>
                        <select name="categoria_vaga" class="form-select car-form-select" required>
                            <option value="Carro" {{ old('categoria_vaga', $anuncio->categoria_vaga) == 'Carro' ? 'selected' : '' }}>Carro</option>
                            <option value="Moto" {{ old('categoria_vaga', $anuncio->categoria_vaga) == 'Moto' ? 'selected' : '' }}>Moto</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bloco 4: Disponibilidade -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-calendar-week-fill"></i> Disponibilidade Semanal e Horários
                </div>

                <div class="mb-4">
                    <label class="car-form-label d-block">
                        <i class="bi bi-calendar-week"></i> Dias da Semana Disponíveis
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
                            <label class="cs-day-pill-label" for="edit_dia_{{ $chave }}">
                                {{ $nome }}
                            </label>
                        @endforeach
                    </div>
                    <div class="form-text text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i> Selecione os dias em que a vaga estará aberta para receber veículos.
                    </div>
                    @error('dias_disponiveis')
                        <div class="small mt-2 fw-medium" style="color: var(--cs-danger-text);">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
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

                    $opcoesInicio = $horariosPadrao;
                    if ($horarioInicioSalvo && !in_array($horarioInicioSalvo, $opcoesInicio)) {
                        $opcoesInicio[] = $horarioInicioSalvo;
                        sort($opcoesInicio);
                    }
                    if ($horarioInicioSelecionado && !in_array($horarioInicioSelecionado, $opcoesInicio)) {
                        $opcoesInicio[] = $horarioInicioSelecionado;
                        sort($opcoesInicio);
                    }

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

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-clock"></i> Horário Início (Abertura)</label>
                        <select name="horario_inicio" class="form-select car-form-select" required>
                            <option value="">Selecione o horário...</option>
                            @foreach($opcoesInicio as $h)
                                <option value="{{ $h }}" {{ $horarioInicioSelecionado == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                        @error('horario_inicio')
                            <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-clock-history"></i> Horário Fim (Fechamento)</label>
                        <select name="horario_fim" class="form-select car-form-select" required>
                            <option value="">Selecione o horário...</option>
                            @foreach($opcoesFim as $h)
                                <option value="{{ $h }}" {{ $horarioFimSelecionado == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                        @error('horario_fim')
                            <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top" style="border-color: var(--cs-border) !important;">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const MAX_FOTOS = {{ $maxFotos }};
    const MAX_BYTES = 5 * 1024 * 1024;
    const TIPOS_ACEITOS = ['image/jpeg', 'image/png', 'image/webp'];

    const inputNovas = document.getElementById('input_fotos_edit');
    const dropzone = document.getElementById('dropzone_fotos_edit');
    const previewGrid = document.getElementById('preview_fotos_edit');
    const alertaBox = document.getElementById('alerta_fotos_edit');
    const alertaMsg = document.getElementById('alerta_fotos_edit_msg');

    let novasSelecionadas = [];

    function mostrarAviso(texto) {
        if (!alertaBox || !alertaMsg) return;
        if (texto) {
            alertaMsg.textContent = texto;
            alertaBox.classList.remove('d-none');
        } else {
            alertaBox.classList.add('d-none');
        }
    }

    function contarExistentesMantidas() {
        const checkboxes = document.querySelectorAll('.js-remover-foto-checkbox');
        let mantidas = 0;
        checkboxes.forEach(function (cb) {
            if (!cb.checked) mantidas++;
        });
        return mantidas;
    }

    function formatarTamanho(bytes) {
        if (bytes < 1024 * 1024) {
            return Math.max(1, Math.round(bytes / 1024)) + ' KB';
        }
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    // Marcar / desfazer exclusão explícita de fotos existentes
    document.querySelectorAll('.js-btn-toggle-remover').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const photoId = btn.getAttribute('data-photo-id');
            const checkbox = document.getElementById('remover_foto_' + photoId);
            const card = document.querySelector('[data-photo-card="' + photoId + '"]');
            if (!checkbox || !card) return;

            checkbox.checked = !checkbox.checked;
            card.classList.toggle('is-marked-delete', checkbox.checked);

            const icon = btn.querySelector('i');
            const label = btn.querySelector('span');
            if (checkbox.checked) {
                if (icon) icon.className = 'bi bi-arrow-counterclockwise';
                if (label) label.textContent = 'Desfazer';
            } else {
                if (icon) icon.className = 'bi bi-trash';
                if (label) label.textContent = 'Excluir';
            }
            mostrarAviso('');
        });
    });

    // Substituir foto individual com prévia imediata
    document.querySelectorAll('.js-btn-substituir').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const photoId = btn.getAttribute('data-photo-id');
            const fileInput = document.getElementById('substituir_foto_' + photoId);
            if (fileInput) fileInput.click();
        });
    });

    document.querySelectorAll('.js-substituir-foto-input').forEach(function (fileInput) {
        fileInput.addEventListener('change', function () {
            const photoId = fileInput.getAttribute('data-photo-id');
            const img = document.getElementById('img_existente_' + photoId);
            const card = document.querySelector('[data-photo-card="' + photoId + '"]');
            const statusBadge = document.getElementById('status_subst_' + photoId);
            const btnDesfazer = card ? card.querySelector('.js-btn-desfazer-subst') : null;
            const checkboxRemover = document.getElementById('remover_foto_' + photoId);
            const btnRemover = card ? card.querySelector('.js-btn-toggle-remover') : null;

            const file = fileInput.files && fileInput.files[0];
            if (!file) return;

            if (!TIPOS_ACEITOS.includes(file.type)) {
                mostrarAviso('Formato inválido para substituição. Use JPG, PNG ou WEBP.');
                fileInput.value = '';
                return;
            }
            if (file.size > MAX_BYTES) {
                mostrarAviso('A imagem substituta excede o limite de 5 MB.');
                fileInput.value = '';
                return;
            }

            // Desmarca exclusão se usuário escolheu substituir
            if (checkboxRemover && checkboxRemover.checked) {
                checkboxRemover.checked = false;
                if (card) card.classList.remove('is-marked-delete');
                if (btnRemover) {
                    const icon = btnRemover.querySelector('i');
                    const label = btnRemover.querySelector('span');
                    if (icon) icon.className = 'bi bi-trash';
                    if (label) label.textContent = 'Excluir';
                }
            }

            if (img) {
                const objectUrl = URL.createObjectURL(file);
                img.src = objectUrl;
                img.onload = function () { URL.revokeObjectURL(objectUrl); };
            }
            if (card) card.classList.add('is-replaced');
            if (statusBadge) statusBadge.classList.remove('d-none');
            if (btnDesfazer) btnDesfazer.classList.remove('d-none');
            mostrarAviso('');
        });
    });

    document.querySelectorAll('.js-btn-desfazer-subst').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const photoId = btn.getAttribute('data-photo-id');
            const fileInput = document.getElementById('substituir_foto_' + photoId);
            const img = document.getElementById('img_existente_' + photoId);
            const card = document.querySelector('[data-photo-card="' + photoId + '"]');
            const statusBadge = document.getElementById('status_subst_' + photoId);

            if (fileInput) fileInput.value = '';
            if (img && img.dataset.originalSrc) img.src = img.dataset.originalSrc;
            if (card) card.classList.remove('is-replaced');
            if (statusBadge) statusBadge.classList.add('d-none');
            btn.classList.add('d-none');
        });
    });

    // Adicionar novas fotografias com prévia
    function sincronizarInputNovas() {
        if (!inputNovas || typeof DataTransfer === 'undefined') return;
        const dt = new DataTransfer();
        novasSelecionadas.forEach(function (file) {
            dt.items.add(file);
        });
        inputNovas.files = dt.files;
    }

    function renderizarPreviasNovas() {
        if (!previewGrid) return;
        previewGrid.innerHTML = '';

        if (novasSelecionadas.length === 0) {
            previewGrid.classList.add('d-none');
            return;
        }

        previewGrid.classList.remove('d-none');

        novasSelecionadas.forEach(function (file, index) {
            const card = document.createElement('div');
            card.className = 'cs-preview-item';

            const thumbBox = document.createElement('div');
            thumbBox.className = 'cs-preview-thumb-box';

            const img = document.createElement('img');
            img.alt = 'Prévia de ' + file.name;
            const objectUrl = URL.createObjectURL(file);
            img.src = objectUrl;
            img.onload = function () { URL.revokeObjectURL(objectUrl); };
            thumbBox.appendChild(img);

            const badgeNova = document.createElement('span');
            badgeNova.className = 'cs-preview-cover-tag';
            badgeNova.style.background = 'var(--cs-teal-600)';
            badgeNova.textContent = 'Nova';
            thumbBox.appendChild(badgeNova);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'cs-preview-remove-btn';
            removeBtn.title = 'Remover da seleção';
            removeBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            removeBtn.addEventListener('click', function () {
                novasSelecionadas.splice(index, 1);
                sincronizarInputNovas();
                mostrarAviso('');
                renderizarPreviasNovas();
            });
            thumbBox.appendChild(removeBtn);

            const info = document.createElement('div');
            info.className = 'p-2';
            info.innerHTML =
                '<div class="small fw-semibold text-truncate cs-text-navy"></div>' +
                '<div class="text-muted cs-tabular" style="font-size: 0.74rem;">' + formatarTamanho(file.size) + '</div>';
            info.firstChild.textContent = file.name;

            card.appendChild(thumbBox);
            card.appendChild(info);
            previewGrid.appendChild(card);
        });
    }

    function adicionarNovasFotos(fileList) {
        const avisos = [];
        const vagasRestantes = Math.max(0, MAX_FOTOS - contarExistentesMantidas());

        Array.from(fileList).forEach(function (file) {
            if (!TIPOS_ACEITOS.includes(file.type)) {
                avisos.push('"' + file.name + '" foi ignorado (use JPG, PNG ou WEBP).');
                return;
            }
            if (file.size > MAX_BYTES) {
                avisos.push('"' + file.name + '" excede o limite de 5 MB.');
                return;
            }
            if (novasSelecionadas.length >= vagasRestantes) {
                avisos.push('Limite máximo de ' + MAX_FOTOS + ' fotografias por anúncio atingido.');
                return;
            }
            novasSelecionadas.push(file);
        });

        sincronizarInputNovas();
        mostrarAviso(avisos.join(' '));
        renderizarPreviasNovas();
    }

    if (inputNovas) {
        inputNovas.addEventListener('change', function () {
            if (inputNovas.files && inputNovas.files.length > 0) {
                adicionarNovasFotos(inputNovas.files);
            }
        });
    }

    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function (ev) {
            dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (ev) {
            dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('is-dragover');
            });
        });

        dropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                adicionarNovasFotos(e.dataTransfer.files);
            }
        });
    }
});
</script>
@endsection
