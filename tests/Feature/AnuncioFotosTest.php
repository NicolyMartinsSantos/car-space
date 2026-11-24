<?php

namespace Tests\Feature;

use App\Models\Anuncio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnuncioFotosTest extends TestCase
{
    use RefreshDatabase;

    private function dadosValidosAnuncio(array $extras = []): array
    {
        return array_merge([
            'titulo' => 'Garagem Coberta Centro',
            'descricao' => 'Vaga ampla com portão eletrônico e câmeras 24h.',
            'endereco' => 'Rua das Flores, 123',
            'cidade' => 'Matinhos',
            'preco' => 35.50,
            'telefone' => '41999990000',
            'quantidade_vagas' => 2,
            'categoria_vaga' => 'Carro',
            'horario_inicio' => '08:00',
            'horario_fim' => '18:00',
            'dias_disponiveis' => ['seg', 'ter', 'qua', 'qui', 'sex'],
        ], $extras);
    }

    public function test_cadastro_de_anuncio_com_fotos_armazena_no_storage_e_exibe_nos_cards_e_detalhes(): void
    {
        Storage::fake('public');

        $anunciante = User::factory()->create();
        $motorista = User::factory()->create();

        $foto1 = UploadedFile::fake()->image('garagem1.jpg', 800, 600);
        $foto2 = UploadedFile::fake()->image('garagem2.png', 800, 600);

        $response = $this->actingAs($anunciante)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'fotos' => [$foto1, $foto2],
        ]));

        $response->assertRedirect('/anuncio');

        $anuncio = Anuncio::with('fotos')->first();
        $this->assertNotNull($anuncio);
        $this->assertCount(2, $anuncio->fotos);

        foreach ($anuncio->fotos as $foto) {
            Storage::disk('public')->assertExists($foto->caminho);
            $this->assertStringStartsWith('anuncios/' . $anuncio->id . '/', $foto->caminho);
        }

        // Exibição no card da página inicial para outro usuário
        $home = $this->actingAs($motorista)->get('/');
        $home->assertOk();
        $home->assertSee($anuncio->fotos->first()->url, false);
        $home->assertSee('2 fotos');

        // Exibição na página de detalhes com galeria
        $show = $this->actingAs($motorista)->get('/anuncio/' . $anuncio->id);
        $show->assertOk();
        $show->assertSee($anuncio->fotos[0]->url, false);
        $show->assertSee($anuncio->fotos[1]->url, false);

        // Exibição em Meus Anúncios
        $index = $this->actingAs($anunciante)->get('/anuncio');
        $index->assertOk();
        $index->assertSee($anuncio->fotos->first()->url, false);
    }

    public function test_cadastro_de_anuncio_sem_fotos_funciona_e_exibe_espaco_reservado(): void
    {
        Storage::fake('public');

        $anunciante = User::factory()->create();
        $motorista = User::factory()->create();

        $response = $this->actingAs($anunciante)->post('/anuncio/store', $this->dadosValidosAnuncio());

        $response->assertRedirect('/anuncio');

        $anuncio = Anuncio::with('fotos')->first();
        $this->assertNotNull($anuncio);
        $this->assertCount(0, $anuncio->fotos);

        $home = $this->actingAs($motorista)->get('/');
        $home->assertOk();
        $home->assertSee('Sem fotografias cadastradas');

        $show = $this->actingAs($motorista)->get('/anuncio/' . $anuncio->id);
        $show->assertOk();
        $show->assertSee('Sem fotografias cadastradas');
    }

    public function test_validacao_rejeita_formato_invalido_tamanho_excessivo_e_limite_de_quantidade(): void
    {
        Storage::fake('public');

        $anunciante = User::factory()->create();

        // Formato inválido
        $arquivoTexto = UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf');
        $resFormato = $this->actingAs($anunciante)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'fotos' => [$arquivoTexto],
        ]));
        $resFormato->assertSessionHasErrors('fotos.0');

        // Tamanho acima de 5 MB (6000 KB)
        $arquivoGrande = UploadedFile::fake()->image('enorme.jpg')->size(6000);
        $resTamanho = $this->actingAs($anunciante)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'fotos' => [$arquivoGrande],
        ]));
        $resTamanho->assertSessionHasErrors('fotos.0');

        // Mais de 6 fotos
        $seteFotos = [];
        for ($i = 0; $i < 7; $i++) {
            $seteFotos[] = UploadedFile::fake()->image("foto_{$i}.jpg");
        }
        $resQtd = $this->actingAs($anunciante)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'fotos' => $seteFotos,
        ]));
        $resQtd->assertSessionHasErrors('fotos');

        $this->assertEquals(0, Anuncio::count());
    }

    public function test_edicao_preserva_fotos_existentes_permite_adicionar_substituir_e_excluir_sem_afetar_outros_anuncios(): void
    {
        Storage::fake('public');

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Anúncio de outro usuário (deve permanecer intocado)
        $this->actingAs($userB)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'titulo' => 'Garagem Outro Usuário',
            'fotos' => [UploadedFile::fake()->image('outro.jpg')],
        ]));
        $anuncioOutro = Anuncio::where('user_id', $userB->id)->with('fotos')->first();
        $fotoOutro = $anuncioOutro->fotos->first();

        // Anúncio do usuário A com 2 fotos
        $this->actingAs($userA)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'fotos' => [
                UploadedFile::fake()->image('a1.jpg'),
                UploadedFile::fake()->image('a2.jpg'),
            ],
        ]));
        $anuncio = Anuncio::where('user_id', $userA->id)->with('fotos')->first();
        $foto1 = $anuncio->fotos[0];
        $foto2 = $anuncio->fotos[1];
        $caminhoFoto1Antigo = $foto1->caminho;
        $caminhoFoto2 = $foto2->caminho;

        // 1. Edição apenas de texto não apaga fotos existentes
        $this->actingAs($userA)->post('/anuncio/update/' . $anuncio->id, $this->dadosValidosAnuncio([
            'titulo' => 'Garagem Atualizada Sem Mexer Nas Fotos',
        ]))->assertRedirect('/anuncio');

        $anuncio->refresh()->load('fotos');
        $this->assertCount(2, $anuncio->fotos);
        Storage::disk('public')->assertExists($caminhoFoto1Antigo);
        Storage::disk('public')->assertExists($caminhoFoto2);

        // 2. Edição: substituir foto 1, excluir foto 2, tentar remover foto de outro usuário (deve ser ignorado), e adicionar foto 3
        $this->actingAs($userA)->post('/anuncio/update/' . $anuncio->id, $this->dadosValidosAnuncio([
            'titulo' => 'Garagem Com Fotos Atualizadas',
            'remover_fotos' => [$foto2->id, $fotoOutro->id],
            'substituir_fotos' => [
                $foto1->id => UploadedFile::fake()->image('a1_nova.webp'),
            ],
            'fotos' => [
                UploadedFile::fake()->image('a3.png'),
            ],
        ]))->assertRedirect('/anuncio');

        $anuncio->refresh()->load('fotos');
        $this->assertCount(2, $anuncio->fotos);

        // Foto 1 foi substituída: caminho mudou, antigo removido, novo existe
        $foto1Atualizada = $anuncio->fotos->firstWhere('id', $foto1->id);
        $this->assertNotNull($foto1Atualizada);
        $this->assertNotEquals($caminhoFoto1Antigo, $foto1Atualizada->caminho);
        Storage::disk('public')->assertMissing($caminhoFoto1Antigo);
        Storage::disk('public')->assertExists($foto1Atualizada->caminho);

        // Foto 2 foi excluída do banco e do disco
        $this->assertNull($anuncio->fotos->firstWhere('id', $foto2->id));
        Storage::disk('public')->assertMissing($caminhoFoto2);

        // Nova foto 3 existe no disco
        $foto3 = $anuncio->fotos->firstWhere('id', '!=', $foto1->id);
        $this->assertNotNull($foto3);
        Storage::disk('public')->assertExists($foto3->caminho);

        // Foto do outro usuário permanece 100% intacta!
        $this->assertDatabaseHas('anuncio_fotos', ['id' => $fotoOutro->id]);
        Storage::disk('public')->assertExists($fotoOutro->caminho);
    }

    public function test_exclusao_de_anuncio_remove_imagens_e_diretorio_do_storage(): void
    {
        Storage::fake('public');

        $anunciante = User::factory()->create();

        $this->actingAs($anunciante)->post('/anuncio/store', $this->dadosValidosAnuncio([
            'fotos' => [
                UploadedFile::fake()->image('del1.jpg'),
                UploadedFile::fake()->image('del2.png'),
            ],
        ]));

        $anuncio = Anuncio::with('fotos')->first();
        $caminhos = $anuncio->fotos->pluck('caminho')->all();
        $this->assertCount(2, $caminhos);

        $this->actingAs($anunciante)->get('/anuncio/delete/' . $anuncio->id)
            ->assertRedirect('/anuncio');

        $this->assertDatabaseMissing('anuncios', ['id' => $anuncio->id]);
        $this->assertDatabaseCount('anuncio_fotos', 0);

        foreach ($caminhos as $caminho) {
            Storage::disk('public')->assertMissing($caminho);
        }
        $this->assertFalse(Storage::disk('public')->exists('anuncios/' . $anuncio->id));
    }
}
