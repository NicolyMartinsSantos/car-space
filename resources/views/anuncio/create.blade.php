<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <h1>Cadastrar Anúncio</h1>

    <form action="/anuncio/store" method="POST">

        @csrf

        <div class="mb-3">
            <input type="text"
                   name="titulo"
                   placeholder="Título"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <textarea name="descricao"
                      placeholder="Descrição"
                      class="form-control"
                      required></textarea>
        </div>

        <!-- CAMPO DE CEP -->
        <div class="mb-3">
            <input type="text"
                   id="cep"
                   name="cep"
                   placeholder="CEP (digite para buscar o endereço)"
                   class="form-control"
                   maxlength="9"
                   onblur="buscarCep()">
        </div>

        <div class="mb-3">
            <input type="text"
                   id="endereco"
                   name="endereco"
                   placeholder="Endereço (preenchido automaticamente)"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <input type="number"
                   step="0.01"
                   name="preco"
                   placeholder="Preço"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <input type="text"
                   name="telefone"
                   placeholder="Telefone"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <input type="number"
                   name="quantidade_vagas"
                   placeholder="Quantidade de vagas"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <input type="text"
                   name="categoria_vaga"
                   placeholder="Categoria da vaga (Carro ou Moto)"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Horário início</label>

            <input type="time"
                   name="horario_inicio"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Horário fim</label>

            <input type="time"
                   name="horario_fim"
                   class="form-control"
                   required>
        </div>

        <button type="submit" class="btn btn-success">
            Salvar
        </button>

        <a href="/" class="btn btn-secondary">
            Voltar
        </a>

    </form>

</div>

<!-- SCRIPT DA CONSULTA DO VIACEP -->
<script>
function buscarCep() {
    let cep = document.getElementById('cep').value.replace(/\D/g, '');

    if (cep.length === 8) {
        document.getElementById('endereco').value = "Buscando endereço...";

        fetch(`/buscar-cep?cep=${cep}`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    let enderecoCompleto = `${data.logradouro || ''}, ${data.bairro || ''} - ${data.localidade || ''}/${data.uf || ''}`;
                    document.getElementById('endereco').value = enderecoCompleto;
                } else {
                    alert('CEP não encontrado!');
                    document.getElementById('endereco').value = "";
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao buscar o CEP.');
                document.getElementById('endereco').value = "";
            });
    }
}
</script>