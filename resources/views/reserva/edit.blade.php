<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <h1>Editar Reserva</h1>

    <form action="/reserva/update/{{ $reserva->id }}" method="POST">

        @csrf

        <div class="mb-3">

            <label>Data</label>

            <input type="date"
                   name="data"
                   value="{{ $reserva->data }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <label>Horário</label>

            <input type="time"
                   name="horario"
                   value="{{ $reserva->horario }}"
                   class="form-control">

        </div>

        <div class="mb-3">

            <label>Status</label>

            <select name="status" class="form-control">

                <option value="Pendente"
                {{ $reserva->status == 'Pendente' ? 'selected' : '' }}>

                    Pendente

                </option>

                <option value="Confirmada"
                {{ $reserva->status == 'Confirmada' ? 'selected' : '' }}>

                    Confirmada

                </option>

                <option value="Cancelada"
                {{ $reserva->status == 'Cancelada' ? 'selected' : '' }}>

                    Cancelada

                </option>

            </select>

        </div>

       <div class="mb-3">

    <label>Veículo</label>

    <select name="veiculo_id" class="form-control">

        @foreach($veiculos as $veiculo)

        <option value="{{ $veiculo->id }}"
            {{ $reserva->veiculo_id == $veiculo->id ? 'selected' : '' }}>

            {{ $veiculo->modelo }}

        </option>

        @endforeach

    </select>

</div>

        <div class="mb-3">

    <label>Anúncio</label>

    <select name="anuncio_id" class="form-control">

        @foreach($anuncios as $anuncio)

        <option value="{{ $anuncio->id }}"
            {{ $reserva->anuncio_id == $anuncio->id ? 'selected' : '' }}>

            {{ $anuncio->titulo }}

        </option>

        @endforeach

    </select>

</div>

        <button type="submit"
                class="btn btn-primary">

            Atualizar

        </button>

        <a href="/reserva"
           class="btn btn-secondary">

            Voltar

        </a>

    </form>

</div>