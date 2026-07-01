<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <h1>Cadastrar Veículo</h1>

    <form action="/veiculo/store" method="POST">

        @csrf

        <div class="mb-3">

            <input type="text"
                   name="placa"
                   placeholder="Placa"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="text"
                   name="modelo"
                   placeholder="Modelo"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="text"
                   name="cor"
                   placeholder="Cor"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="number"
                   name="ano"
                   placeholder="Ano"
                   class="form-control">

        </div>

        <div class="mb-3">

            <select name="categoria" class="form-control">

                <option value="">Selecione a categoria</option>

                <option value="Carro">Carro</option>

                <option value="Moto">Moto</option>

            </select>

        </div>

        <button type="submit" class="btn btn-success">

            Salvar

        </button>

        <a href="/veiculo" class="btn btn-secondary">

            Voltar

        </a>

    </form>

</div>