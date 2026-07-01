<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

<h1>Anúncios Cadastrados</h1>

<a href="/anuncio/create" class="btn btn-success mb-3">

    Novo Anúncio

</a>

<a href="/"
class="btn btn-dark">

🏠 Página Inicial

</a>

<br><br>

<table class="table table-bordered table-striped">

<tr>

    <th>ID</th>

    <th>TÍTULO</th>

    <th>ENDEREÇO</th>

    <th>PREÇO</th>

    <th>VAGAS</th>

    <th>CATEGORIA</th>

    <th>AÇÕES</th>

</tr>

@foreach($data as $anuncio)

<tr>

    <td>{{ $anuncio->id }}</td>

    <td>{{ $anuncio->titulo }}</td>

    <td>{{ $anuncio->endereco }}</td>

    <td>R$ {{ $anuncio->preco }}</td>

    <td>{{ $anuncio->quantidade_vagas }}</td>

    <td>{{ $anuncio->categoria_vaga }}</td>

    <td>

    @guest

        <a href="/login"
           class="btn btn-warning btn-sm">

            Reservar

        </a>

    @endguest


    @auth

        @if($anuncio->user_id == auth()->id())

            <a href="/anuncio/edit/{{ $anuncio->id }}"
               class="btn btn-primary btn-sm">

                Editar

            </a>

            <br><br>

            <a href="/anuncio/delete/{{ $anuncio->id }}"
               class="btn btn-danger btn-sm">

                Excluir

            </a>

        @else

            <a href="/reserva/create"
               class="btn btn-warning btn-sm">

                Reservar

            </a>

        @endif

    @endauth

</td>

</tr>

@endforeach

</table>

</div>