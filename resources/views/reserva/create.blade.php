<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

<h1>Cadastrar Reserva</h1>

<form action="/reserva/store" method="POST">

@csrf

<div class="mb-3">

<input type="date"

name="data"

class="form-control">

</div>

<div class="mb-3">

<input type="time"

name="horario"

class="form-control">

</div>


<div class="mb-3">

<label class="form-label">

Veículo

</label>

<select name="veiculo_id" class="form-control">

@foreach($veiculos as $veiculo)

<option value="{{ $veiculo->id }}">

{{ $veiculo->modelo }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label class="form-label">

Anúncio

</label>

<select name="anuncio_id" class="form-control">

@foreach($anuncios as $anuncio)

<option value="{{ $anuncio->id }}">

{{ $anuncio->titulo }}

</option>

@endforeach

</select>

</div>

<button type="submit"

class="btn btn-success">

Salvar

</button>

<a href="/reserva"

class="btn btn-secondary">

Voltar

</a>

</form>

</div>