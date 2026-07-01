<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <h1>Editar Veículo</h1>

    <form action="/veiculo/update/{{ $veiculo->id }}" method="POST">

        @csrf

        <div class="mb-3">

            <input type="text"
                   name="placa"
                   value="{{ $veiculo->placa }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="text"
                   name="modelo"
                   value="{{ $veiculo->modelo }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="text"
                   name="cor"
                   value="{{ $veiculo->cor }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <input type="number"
                   name="ano"
                   value="{{ $veiculo->ano }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <select name="categoria" class="form-control">

                <option value="Carro"
                {{ $veiculo->categoria == 'Carro' ? 'selected' : '' }}>

                    Carro

                </option>

                <option value="Moto"
                {{ $veiculo->categoria == 'Moto' ? 'selected' : '' }}>

                    Moto

                </option>

            </select>

        </div>

        <button type="submit" class="btn btn-primary">

            Atualizar

        </button>

        <a href="/veiculo" class="btn btn-secondary">

            Voltar

        </a>

    </form>

</div>