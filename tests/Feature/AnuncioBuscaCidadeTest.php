<?php

namespace Tests\Feature;

use App\Models\Anuncio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnuncioBuscaCidadeTest extends TestCase
{
    use RefreshDatabase;

    private function dadosBase(array $extras = []): array
    {
        return array_merge([
            'titulo' => 'Garagem Segura',
            'descricao' => 'Vaga coberta de fácil acesso.',
            'endereco' => 'Rua Central, 100',
            'cidade' => 'Matinhos',
            'preco' => 30.00,
            'telefone' => '41999998888',
            'quantidade_vagas' => 1,
            'categoria_vaga' => 'Carro',
            'horario_inicio' => '08:00',
            'horario_fim' => '18:00',
            'dias_disponiveis' => ['seg', 'ter', 'qua', 'qui', 'sex'],
        ], $extras);
    }

    public function test_cadastro_de_novo_anuncio_exige_cidade_obrigatoria(): void
    {
        $user = User::factory()->create();

        $resSemCidade = $this->actingAs($user)->post('/anuncio/store', $this->dadosBase([
            'cidade' => '   ',
        ]));

        $resSemCidade->assertSessionHasErrors('cidade');
        $this->assertDatabaseCount('anuncios', 0);

        $resComCidade = $this->actingAs($user)->post('/anuncio/store', $this->dadosBase([
            'cidade' => '  Pontal do Paraná  ',
        ]));

        $resComCidade->assertRedirect('/anuncio');
        $this->assertDatabaseHas('anuncios', [
            'titulo' => 'Garagem Segura',
            'cidade' => 'Pontal do Paraná',
        ]);
    }

    public function test_edicao_de_anuncio_antigo_permite_cidade_opcional_e_permite_preencher_depois(): void
    {
        $user = User::factory()->create();

        // Simula anúncio antigo sem cidade (cidade = null)
        $anuncioLegado = Anuncio::create($this->dadosBase([
            'titulo' => 'Garagem Legada',
            'cidade' => null,
            'user_id' => $user->id,
        ]));

        // 1. Editar preço/título mantendo cidade vazia deve funcionar normalmente
        $this->actingAs($user)->post('/anuncio/update/' . $anuncioLegado->id, $this->dadosBase([
            'titulo' => 'Garagem Legada Editada',
            'cidade' => '',
        ]))->assertRedirect('/anuncio');

        $anuncioLegado->refresh();
        $this->assertSame('Garagem Legada Editada', $anuncioLegado->titulo);
        $this->assertNull($anuncioLegado->cidade);

        // 2. Informar cidade posteriormente na edição deve salvar normalmente
        $this->actingAs($user)->post('/anuncio/update/' . $anuncioLegado->id, $this->dadosBase([
            'titulo' => 'Garagem Legada Com Cidade',
            'cidade' => 'Guaratuba',
        ]))->assertRedirect('/anuncio');

        $anuncioLegado->refresh();
        $this->assertSame('Guaratuba', $anuncioLegado->cidade);
    }

    public function test_busca_por_cidade_funciona_sem_diferenciar_maiusculas_minusculas_e_acentos(): void
    {
        $anunciante = User::factory()->create();
        $motorista = User::factory()->create();

        Anuncio::create($this->dadosBase([
            'titulo' => 'Vaga Centro Matinhos',
            'cidade' => 'Matinhos',
            'user_id' => $anunciante->id,
        ]));
        Anuncio::create($this->dadosBase([
            'titulo' => 'Vaga Porto Paranaguá',
            'cidade' => 'Paranaguá',
            'user_id' => $anunciante->id,
        ]));
        Anuncio::create($this->dadosBase([
            'titulo' => 'Vaga Praia Pontal',
            'cidade' => 'Pontal do Paraná',
            'user_id' => $anunciante->id,
        ]));
        Anuncio::create($this->dadosBase([
            'titulo' => 'Vaga Antiga Sem Cidade',
            'cidade' => null,
            'user_id' => $anunciante->id,
        ]));

        // Sem busca: exibe todos os 4 anúncios (incluindo o legado sem cidade)
        $todos = $this->actingAs($motorista)->get('/');
        $todos->assertOk();
        $todos->assertSee('Vaga Centro Matinhos');
        $todos->assertSee('Vaga Porto Paranaguá');
        $todos->assertSee('Vaga Praia Pontal');
        $todos->assertSee('Vaga Antiga Sem Cidade');

        // Busca sem acento e minúscula: "paranagua" -> encontra "Paranaguá"
        $resParanagua = $this->actingAs($motorista)->get('/?cidade=paranagua');
        $resParanagua->assertOk();
        $resParanagua->assertSee('Vaga Porto Paranaguá');
        $resParanagua->assertDontSee('Vaga Centro Matinhos');
        $resParanagua->assertDontSee('Vaga Praia Pontal');
        $resParanagua->assertDontSee('Vaga Antiga Sem Cidade');

        // Busca em MAIÚSCULAS e sem acento: "PONTAL DO PARANA" -> encontra "Pontal do Paraná"
        $resPontal = $this->actingAs($motorista)->get('/?cidade=' . urlencode('PONTAL DO PARANA'));
        $resPontal->assertOk();
        $resPontal->assertSee('Vaga Praia Pontal');
        $resPontal->assertDontSee('Vaga Porto Paranaguá');

        // Busca por cidade inexistente: exibe estado vazio amigável e botão para limpar
        $resVazio = $this->actingAs($motorista)->get('/?cidade=Curitiba');
        $resVazio->assertOk();
        $resVazio->assertSee('Nenhuma garagem encontrada em');
        $resVazio->assertSee('Ver Todos os Anúncios');

        // Próprio anunciante logado não vê seus próprios anúncios mesmo filtrando pela cidade
        $resProprio = $this->actingAs($anunciante)->get('/?cidade=Matinhos');
        $resProprio->assertOk();
        $resProprio->assertDontSee('Vaga Centro Matinhos');
    }
}
