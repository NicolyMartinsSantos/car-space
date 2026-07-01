<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $dados = $request->all();

        $dados['user_id'] = auth()->id();

        $this->service->create($dados);

        return redirect('/anuncio');
    }

    public function index()
    {
        $data = Anuncio::all();

        return view('anuncio.index', compact('data'));
    }

    public function edit($id)
    {
        $anuncio = Anuncio::find($id);

        $this->authorize('update', $anuncio);

        return view('anuncio.edit', compact('anuncio'));
    }

    public function update(Request $request, $id)
    {
        $anuncio = Anuncio::find($id);

        $anuncio->titulo = $request->titulo;
        $anuncio->descricao = $request->descricao;
        $anuncio->endereco = $request->endereco;
        $anuncio->preco = $request->preco;
        $anuncio->telefone = $request->telefone;
        $anuncio->quantidade_vagas = $request->quantidade_vagas;
        $anuncio->categoria_vaga = $request->categoria_vaga;
        $anuncio->horario_inicio = $request->horario_inicio;
        $anuncio->horario_fim = $request->horario_fim;
        $anuncio->user_id = auth()->id();

        $anuncio->save();

        return redirect('/anuncio');
    }

    public function delete($id)
    {
        $anuncio = Anuncio::find($id);

        $this->authorize('delete', $anuncio);

        $anuncio->delete();

        return redirect('/anuncio');
    }
}