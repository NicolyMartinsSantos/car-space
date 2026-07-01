<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <h1>Editar Anúncio</h1>

    <form action="/anuncio/update/{{ $anuncio->id }}" method="POST">

        @csrf

        <div class="mb-3">

            <input type="text"
                   name="titulo"
                   value="{{ $anuncio->titulo }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <textarea name="descricao"
                      class="form-control">{{ $anuncio->descricao }}</textarea>

        </div>

        <div class="mb-3">

            <input type="text"
                   name="endereco"
                   value="{{ $anuncio->endereco }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="number"
                   step="0.01"
                   name="preco"
                   value="{{ $anuncio->preco }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="text"
                   name="telefone"
                   value="{{ $anuncio->telefone }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="number"
                   name="quantidade_vagas"
                   value="{{ $anuncio->quantidade_vagas }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="text"
                   name="categoria_vaga"
                   value="{{ $anuncio->categoria_vaga }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <label>Horário início</label>

            <input type="time"
                   name="horario_inicio"
                   value="{{ $anuncio->horario_inicio }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <label>Horário fim</label>

            <input type="time"
                   name="horario_fim"
                   value="{{ $anuncio->horario_fim }}"
                   class="form-control">

        </div>

        <button type="submit" class="btn btn-primary">

            Atualizar

        </button>

        <a href="/anuncio" class="btn btn-secondary">

            Voltar

        </a>

    </form>

</div>