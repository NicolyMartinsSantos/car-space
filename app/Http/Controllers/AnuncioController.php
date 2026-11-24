<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Anuncio;
use App\Services\AnuncioService;

class AnuncioController extends Controller
{
    protected $service;

    public function __construct(AnuncioService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        return view('anuncio.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'cidade' => trim((string) $request->input('cidade')),
        ]);

        $request->validate([
            'cidade'             => ['required', 'string', 'max:100'],
            'dias_disponiveis'   => ['required', 'array', 'min:1'],
            'dias_disponiveis.*' => ['in:seg,ter,qua,qui,sex,sab,dom'],
            'fotos'              => ['nullable', 'array', 'max:' . Anuncio::MAX_FOTOS],
            'fotos.*'            => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'cidade.required'           => 'Informe a cidade onde a vaga está localizada.',
            'cidade.max'                => 'O nome da cidade pode ter no máximo 100 caracteres.',
            'dias_disponiveis.required' => 'Selecione ao menos um dia da semana disponível.',
            'dias_disponiveis.min'      => 'Selecione ao menos um dia da semana disponível.',
            'dias_disponiveis.*.in'     => 'O dia da semana selecionado é inválido.',
            'fotos.max'                 => 'Você pode enviar no máximo ' . Anuncio::MAX_FOTOS . ' fotografias por anúncio.',
            'fotos.*.image'             => 'Cada arquivo enviado deve ser uma imagem válida.',
            'fotos.*.mimes'             => 'Formatos de imagem aceitos: JPG, JPEG, PNG ou WEBP.',
            'fotos.*.max'               => 'Cada fotografia pode ter no máximo 5 MB.',
        ]);

        $dados = $request->except(['fotos']);
        $dados['user_id'] = auth()->id();

        $arquivosSalvos = [];

        try {
            DB::transaction(function () use ($dados, $request, &$arquivosSalvos) {
                $anuncio = $this->service->create($dados);

                if ($request->hasFile('fotos')) {
                    $ordem = 0;
                    foreach ($request->file('fotos') as $arquivo) {
                        if ($arquivo && $arquivo->isValid()) {
                            $caminho = $arquivo->store('anuncios/' . $anuncio->id, 'public');
                            $arquivosSalvos[] = $caminho;

                            $anuncio->fotos()->create([
                                'caminho' => $caminho,
                                'ordem'   => $ordem++,
                            ]);
                        }
                    }
                }
            });
        } catch (\Throwable $e) {
            foreach ($arquivosSalvos as $caminho) {
                if (Storage::disk('public')->exists($caminho)) {
                    Storage::disk('public')->delete($caminho);
                }
            }

            throw $e;
        }

        return redirect('/anuncio')->with('success', 'Anúncio cadastrado com sucesso!');
    }

    public function index()
    {
        $data = Anuncio::where('user_id', auth()->id())
            ->with(['fotos', 'reservas.veiculo', 'reservas.user'])
            ->get();

        return view('anuncio.index', compact('data'));
    }

    public function edit($id)
    {
        $anuncio = Anuncio::with('fotos')->findOrFail($id);

        $this->authorize('update', $anuncio);

        return view('anuncio.edit', compact('anuncio'));
    }

    public function update(Request $request, $id)
    {
        $anuncio = Anuncio::with('fotos')->findOrFail($id);

        $this->authorize('update', $anuncio);

        $cidadeNormalizada = trim((string) $request->input('cidade', ''));
        $request->merge([
            'cidade' => $cidadeNormalizada !== '' ? $cidadeNormalizada : null,
        ]);

        $request->validate([
            'cidade'             => ['nullable', 'string', 'max:100'],
            'dias_disponiveis'   => ['required', 'array', 'min:1'],
            'dias_disponiveis.*' => ['in:seg,ter,qua,qui,sex,sab,dom'],
            'fotos'              => ['nullable', 'array', 'max:' . Anuncio::MAX_FOTOS],
            'fotos.*'            => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remover_fotos'      => ['nullable', 'array'],
            'remover_fotos.*'    => ['integer'],
            'substituir_fotos'   => ['nullable', 'array'],
            'substituir_fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'cidade.max'                => 'O nome da cidade pode ter no máximo 100 caracteres.',
            'dias_disponiveis.required' => 'Selecione ao menos um dia da semana disponível.',
            'dias_disponiveis.min'      => 'Selecione ao menos um dia da semana disponível.',
            'dias_disponiveis.*.in'     => 'O dia da semana selecionado é inválido.',
            'fotos.max'                 => 'Você pode enviar no máximo ' . Anuncio::MAX_FOTOS . ' fotografias por anúncio.',
            'fotos.*.image'             => 'Cada arquivo enviado deve ser uma imagem válida.',
            'fotos.*.mimes'             => 'Formatos de imagem aceitos: JPG, JPEG, PNG ou WEBP.',
            'fotos.*.max'               => 'Cada fotografia pode ter no máximo 5 MB.',
            'substituir_fotos.*.image'  => 'O arquivo substituto deve ser uma imagem válida.',
            'substituir_fotos.*.mimes'  => 'Formatos de imagem aceitos: JPG, JPEG, PNG ou WEBP.',
            'substituir_fotos.*.max'    => 'Cada fotografia pode ter no máximo 5 MB.',
        ]);

        // Filtra apenas IDs de fotos que realmente pertencem a este anúncio
        $idsParaRemover = $anuncio->fotos()
            ->whereIn('id', array_map('intval', (array) $request->input('remover_fotos', [])))
            ->pluck('id')
            ->all();

        $quantidadeExistentesMantidas = $anuncio->fotos->count() - count($idsParaRemover);
        $novasFotos = $request->hasFile('fotos') ? array_filter($request->file('fotos')) : [];

        if (($quantidadeExistentesMantidas + count($novasFotos)) > Anuncio::MAX_FOTOS) {
            return back()
                ->withErrors([
                    'fotos' => 'Cada anúncio pode ter no máximo ' . Anuncio::MAX_FOTOS . ' fotografias. Remova alguma foto existente antes de adicionar mais imagens.',
                ])
                ->withInput();
        }

        $arquivosNovosSalvos = [];
        $arquivosAntigosParaApagar = [];

        try {
            DB::transaction(function () use (
                $anuncio,
                $request,
                $idsParaRemover,
                $novasFotos,
                &$arquivosNovosSalvos,
                &$arquivosAntigosParaApagar
            ) {
                $anuncio->titulo = $request->titulo;
                $anuncio->descricao = $request->descricao;
                $anuncio->endereco = $request->endereco;
                $anuncio->cidade = $request->input('cidade');
                $anuncio->preco = $request->preco;
                $anuncio->telefone = $request->telefone;
                $anuncio->quantidade_vagas = $request->quantidade_vagas;
                $anuncio->categoria_vaga = $request->categoria_vaga;
                $anuncio->horario_inicio = $request->horario_inicio;
                $anuncio->horario_fim = $request->horario_fim;
                $anuncio->dias_disponiveis = $request->dias_disponiveis;
                $anuncio->user_id = auth()->id();

                $anuncio->save();

                // 1. Remover somente fotos explicitamente marcadas para exclusão pertencentes a este anúncio
                if (!empty($idsParaRemover)) {
                    $fotosRemover = $anuncio->fotos()->whereIn('id', $idsParaRemover)->get();
                    foreach ($fotosRemover as $foto) {
                        $arquivosAntigosParaApagar[] = $foto->caminho;
                        $foto->delete();
                    }
                }

                // 2. Substituir fotos individuais explicitamente selecionadas (exceto as marcadas para remoção)
                $substituicoes = $request->file('substituir_fotos', []);
                if (is_array($substituicoes) && !empty($substituicoes)) {
                    foreach ($substituicoes as $fotoId => $arquivoNovo) {
                        $fotoIdInt = (int) $fotoId;
                        if (in_array($fotoIdInt, $idsParaRemover, true)) {
                            continue;
                        }
                        if (!$arquivoNovo || !$arquivoNovo->isValid()) {
                            continue;
                        }

                        $fotoExistente = $anuncio->fotos()->where('id', $fotoIdInt)->first();
                        if (!$fotoExistente) {
                            continue;
                        }

                        $novoCaminho = $arquivoNovo->store('anuncios/' . $anuncio->id, 'public');
                        $arquivosNovosSalvos[] = $novoCaminho;
                        $arquivosAntigosParaApagar[] = $fotoExistente->caminho;

                        $fotoExistente->caminho = $novoCaminho;
                        $fotoExistente->save();
                    }
                }

                // 3. Adicionar novas fotografias sem afetar as existentes
                if (!empty($novasFotos)) {
                    $proximaOrdem = ((int) $anuncio->fotos()->max('ordem')) + 1;
                    if ($anuncio->fotos()->count() === 0) {
                        $proximaOrdem = 0;
                    }

                    foreach ($novasFotos as $arquivo) {
                        if ($arquivo && $arquivo->isValid()) {
                            $caminho = $arquivo->store('anuncios/' . $anuncio->id, 'public');
                            $arquivosNovosSalvos[] = $caminho;

                            $anuncio->fotos()->create([
                                'caminho' => $caminho,
                                'ordem'   => $proximaOrdem++,
                            ]);
                        }
                    }
                }
            });
        } catch (\Throwable $e) {
            // Rollback do disco caso a transação no banco falhe
            foreach ($arquivosNovosSalvos as $caminho) {
                if (Storage::disk('public')->exists($caminho)) {
                    Storage::disk('public')->delete($caminho);
                }
            }

            throw $e;
        }

        // Apaga do disco os arquivos antigos somente após o commit bem-sucedido no banco
        foreach ($arquivosAntigosParaApagar as $caminhoAntigo) {
            if (!empty($caminhoAntigo) && Storage::disk('public')->exists($caminhoAntigo)) {
                Storage::disk('public')->delete($caminhoAntigo);
            }
        }

        return redirect('/anuncio')->with('success', 'Anúncio atualizado com sucesso!');
    }

    public function delete($id)
    {
        $anuncio = Anuncio::with('fotos')->findOrFail($id);

        $this->authorize('delete', $anuncio);

        $anuncio->delete();

        return redirect('/anuncio')->with('success', 'Anúncio excluído com sucesso!');
    }

    public function show($id)
    {
        $anuncio = Anuncio::with('fotos')->findOrFail($id);

        return view('anuncio.show', compact('anuncio'));
    }
}
