<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <h1>Cadastrar Anúncio</h1>

    <form action="/anuncio/store" method="POST">

        @csrf

        <div class="mb-3">
            <input type="text"
                   name="titulo"
                   placeholder="Título"
                   class="form-control">
        </div>

        <div class="mb-3">
            <textarea name="descricao"
                      placeholder="Descrição"
                      class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <input type="text"
                   name="endereco"
                   placeholder="Endereço"
                   class="form-control">
        </div>

        <div class="mb-3">
            <input type="number"
                   step="0.01"
                   name="preco"
                   placeholder="Preço"
                   class="form-control">
        </div>

        <div class="mb-3">
            <input type="text"
                   name="telefone"
                   placeholder="Telefone"
                   class="form-control">
        </div>

        <div class="mb-3">
            <input type="number"
                   name="quantidade_vagas"
                   placeholder="Quantidade de vagas"
                   class="form-control">
        </div>

        <div class="mb-3">
            <input type="text"
                   name="categoria_vaga"
                   placeholder="Categoria da vaga (Carro ou Moto)"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Horário início</label>

            <input type="time"
                   name="horario_inicio"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Horário fim</label>

            <input type="time"
                   name="horario_fim"
                   class="form-control">
        </div>

        <button type="submit" class="btn btn-success">
            Salvar
        </button>

        <a href="/anuncio" class="btn btn-secondary">
            Voltar
        </a>

    </form>

</div>