<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

<h1>Reservas</h1>


<a href="/"
class="btn btn-dark">

🏠 Página Inicial

</a>

<table class="table table-bordered">

<tr>

<th>ID</th>

<th>DATA</th>

<th>HORÁRIO</th>

<th>STATUS</th>

<th>VEÍCULO</th>

<th>ANÚNCIO</th>

<th>AÇÕES</th>

</tr>

@foreach($data as $reserva)

<tr>

<td>{{ $reserva->id }}</td>

<td>{{ $reserva->data }}</td>

<td>{{ $reserva->horario }}</td>

<td>{{ $reserva->status }}</td>

<td>{{ $reserva->veiculo?->modelo ?? 'Veículo não encontrado' }}</td>

<td>{{ $reserva->anuncio?->titulo ?? 'Anúncio não encontrado' }}</td>

<td>

<a href="/reserva/edit/{{ $reserva->id }}"

class="btn btn-primary btn-sm">

Editar

</a>

<br><br>

<a href="/reserva/delete/{{ $reserva->id }}"

class="btn btn-danger btn-sm">

Excluir

</a>

</td>

</tr>

@endforeach

</table>

</div>