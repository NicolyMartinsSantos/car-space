<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

<h1>Meus Veículos </h1>

<a href="/veiculo/create" class="btn btn-success mb-3">

    Novo Veículo

</a>

<a href="/"
class="btn btn-dark mb-3">

🏠 Página Inicial

</a>

<br><br>

<table class="table table-bordered table-striped">

<tr>

    <th>ID</th>

    <th>PLACA</th>

    <th>MODELO</th>

    <th>COR</th>

    <th>ANO</th>

    <th>CATEGORIA</th>

    <th>AÇÕES</th>

</tr>

@foreach($data as $veiculo)

<tr>

    <td>{{ $veiculo->id }}</td>

    <td>{{ $veiculo->placa }}</td>

    <td>{{ $veiculo->modelo }}</td>

    <td>{{ $veiculo->cor }}</td>

    <td>{{ $veiculo->ano }}</td>

    <td>{{ $veiculo->categoria }}</td>

    <td>

        <a href="/veiculo/edit/{{ $veiculo->id }}"
           class="btn btn-primary btn-sm">

            Editar

        </a>

        <br><br>

        <a href="/veiculo/delete/{{ $veiculo->id }}"
           class="btn btn-danger btn-sm">

            Excluir

        </a>

    </td>

</tr>

@endforeach

</table>

</div>