@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 840px;">
    <div class="cs-page-header">
        <div>
            <h1 class="cs-page-title">Cadastrar Anúncio</h1>
            <p class="cs-page-subtitle">Preencha os detalhes abaixo para disponibilizar sua vaga de garagem</p>
        </div>
        <a href="/anuncio" class="btn-car-secondary btn-car-sm btn-car-pill">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="car-card p-4 p-md-5">
        <form action="/anuncio/store" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Bloco 1: Informações da Vaga -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-card-heading"></i> Informações Principais
                </div>

                <div class="mb-3">
                    <label class="car-form-label"><i class="bi bi-fonts"></i> Título do Anúncio</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" placeholder="Ex: Vaga coberta no centro da cidade" class="form-control car-form-control" required>
                </div>

                <div>
                    <label class="car-form-label"><i class="bi bi-card-text"></i> Descrição Detalhada</label>
                    <textarea name="descricao" rows="4" placeholder="Descreva os detalhes da sua vaga, facilidades de acesso, segurança, cobertura, etc." class="form-control car-form-control" required>{{ old('descricao') }}</textarea>
                </div>
            </div>

            <!-- Bloco 2: Fotografias da Vaga -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-images"></i> Fotografias da Garagem (Opcional)
                </div>

                <label for="input_fotos_create" id="dropzone_fotos_create" class="cs-upload-dropzone mb-3">
                    <span class="cs-upload-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </span>
                    <div class="fw-bold cs-text-navy mb-1">
                        Clique para selecionar fotografias ou arraste aqui
                    </div>
                    <div class="text-secondary small">
                        Escolha até <strong>{{ \App\Models\Anuncio::MAX_FOTOS }} imagens</strong> do computador ou celular (JPG, PNG ou WEBP &middot; máx. 5 MB por foto)
                    </div>
                    <input
                        type="file"
                        id="input_fotos_create"
                        name="fotos[]"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        class="d-none"
                    >
                </label>

                <div id="alerta_fotos_create" class="cs-alert cs-alert-warning small mb-3 d-none" role="alert">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                    <span id="alerta_fotos_create_msg"></span>
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

                <div id="preview_fotos_create" class="cs-preview-grid d-none"></div>
            </div>

            <!-- Bloco 3: Localização, Preço e Características -->
            <div class="cs-form-section">
                <div class="cs-form-section-title">
                    <i class="bi bi-geo-alt-fill"></i> Localização, Valores e Capacidade
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="car-form-label"><i class="bi bi-geo-alt"></i> Endereço Completo</label>
                        <input type="text" name="endereco" value="{{ old('endereco') }}" placeholder="Rua, Número, Bairro" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-building"></i> Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade') }}" maxlength="100" placeholder="Ex: Matinhos, Curitiba..." class="form-control car-form-control" required>
                        @error('cidade')
                            <div class="small mt-1 fw-medium" style="color: var(--cs-danger-text);"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="car-form-label"><i class="bi bi-currency-dollar"></i> Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" value="{{ old('preco') }}" placeholder="0.00" class="form-control car-form-control" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-telephone"></i> Telefone / WhatsApp</label>
                        <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-car-front"></i> Quantidade de Vagas</label>
                        <input type="number" name="quantidade_vagas" value="{{ old('quantidade_vagas') }}" placeholder="1" class="form-control car-form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="car-form-label"><i class="bi bi-tag"></i> Categoria da Vaga</label>
                        <select name="categoria_vaga" class="form-select car-form-select" required>
                            <option value="">Selecione...</option>
                            <option value="Carro" {{ old('categoria_vaga') == 'Carro' ? 'selected' : '' }}>Carro</option>
                            <option value="Moto" {{ old('categoria_vaga') == 'Moto' ? 'selected' : '' }}>Moto</option>
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
                            $diasSelecionados = old('dias_disponiveis', []);
                        @endphp

                        @foreach($diasSemana as $chave => $nome)
                            <input type="checkbox"
                                   class="btn-check"
                                   name="dias_disponiveis[]"
                                   value="{{ $chave }}"
                                   id="dia_{{ $chave }}"
                                   autocomplete="off"
                                   {{ in_array($chave, $diasSelecionados) ? 'checked' : '' }}>
                            <label class="cs-day-pill-label" for="dia_{{ $chave }}">
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
                @endphp

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="car-form-label"><i class="bi bi-clock"></i> Horário Início (Abertura)</label>
                        <select name="horario_inicio" class="form-select car-form-select" required>
                            <option value="">Selecione o horário...</option>
                            @foreach($horariosPadrao as $h)
                                <option value="{{ $h }}" {{ old('horario_inicio') == $h ? 'selected' : '' }}>
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
                            @foreach($horariosPadrao as $h)
                                <option value="{{ $h }}" {{ old('horario_fim') == $h ? 'selected' : '' }}>
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
                    <i class="bi bi-check-circle"></i> Salvar Anúncio
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('input_fotos_create');
    const dropzone = document.getElementById('dropzone_fotos_create');
    const previewGrid = document.getElementById('preview_fotos_create');
    const alertaBox = document.getElementById('alerta_fotos_create');
    const alertaMsg = document.getElementById('alerta_fotos_create_msg');

    if (!input || !previewGrid) return;

    const MAX_FOTOS = {{ \App\Models\Anuncio::MAX_FOTOS }};
    const MAX_BYTES = 5 * 1024 * 1024;
    const TIPOS_ACEITOS = ['image/jpeg', 'image/png', 'image/webp'];

    let arquivosSelecionados = [];

    function mostrarAviso(texto) {
        if (!alertaBox || !alertaMsg) return;
        if (texto) {
            alertaMsg.textContent = texto;
            alertaBox.classList.remove('d-none');
        } else {
            alertaBox.classList.add('d-none');
        }
    }

    function formatarTamanho(bytes) {
        if (bytes < 1024 * 1024) {
            return Math.max(1, Math.round(bytes / 1024)) + ' KB';
        }
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function sincronizarInput() {
        if (typeof DataTransfer === 'undefined') return;
        const dt = new DataTransfer();
        arquivosSelecionados.forEach(function (file) {
            dt.items.add(file);
        });
        input.files = dt.files;
    }

    function renderizarPrevias() {
        previewGrid.innerHTML = '';

        if (arquivosSelecionados.length === 0) {
            previewGrid.classList.add('d-none');
            return;
        }

        previewGrid.classList.remove('d-none');

        arquivosSelecionados.forEach(function (file, index) {
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

            if (index === 0) {
                const capaTag = document.createElement('span');
                capaTag.className = 'cs-preview-cover-tag';
                capaTag.textContent = 'Capa';
                thumbBox.appendChild(capaTag);
            }

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'cs-preview-remove-btn';
            removeBtn.title = 'Remover imagem';
            removeBtn.setAttribute('aria-label', 'Remover imagem ' + file.name);
            removeBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            removeBtn.addEventListener('click', function () {
                arquivosSelecionados.splice(index, 1);
                sincronizarInput();
                mostrarAviso('');
                renderizarPrevias();
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

    function adicionarArquivos(fileList) {
        const avisos = [];

        Array.from(fileList).forEach(function (file) {
            if (!TIPOS_ACEITOS.includes(file.type)) {
                avisos.push('"' + file.name + '" foi ignorado (use JPG, PNG ou WEBP).');
                return;
            }
            if (file.size > MAX_BYTES) {
                avisos.push('"' + file.name + '" excede o limite de 5 MB.');
                return;
            }
            if (arquivosSelecionados.length >= MAX_FOTOS) {
                avisos.push('Limite máximo de ' + MAX_FOTOS + ' fotografias por anúncio atingido.');
                return;
            }
            arquivosSelecionados.push(file);
        });

        sincronizarInput();
        mostrarAviso(avisos.join(' '));
        renderizarPrevias();
    }

    input.addEventListener('change', function () {
        if (input.files && input.files.length > 0) {
            adicionarArquivos(input.files);
        }
    });

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
                adicionarArquivos(e.dataTransfer.files);
            }
        });
    }
});
</script>
@endsection
