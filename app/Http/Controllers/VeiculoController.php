<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Veiculo;

class VeiculoController extends Controller
{

    public function create()
    {

        return view('veiculo.create');

    }

    public function store(Request $request)
    {

        $veiculo = new Veiculo();

        $veiculo->placa = $request->placa;

        $veiculo->modelo = $request->modelo;

        $veiculo->cor = $request->cor;

        $veiculo->ano = $request->ano;

        $veiculo->categoria = $request->categoria;

        $veiculo->user_id = auth()->id();

        $veiculo->save();

        return redirect('/veiculo');

    }


    public function index()
    {

        $data = Veiculo::where('user_id', auth()->id())->get();

        return view('veiculo.index', compact('data'));

    }


    public function edit($id)
    {

        $veiculo = Veiculo::find($id);

        $this->authorize('update', $veiculo);

        return view('veiculo.edit', compact('veiculo'));

    }


    public function update(Request $request, $id)
    {

        $veiculo = Veiculo::find($id);

        $veiculo->placa = $request->placa;

        $veiculo->modelo = $request->modelo;

        $veiculo->cor = $request->cor;

        $veiculo->ano = $request->ano;

        $veiculo->categoria = $request->categoria;

        $veiculo->save();

        return redirect('/veiculo');

    }


    public function delete($id)
    {

        $veiculo = Veiculo::find($id);

        $this->authorize('delete', $veiculo);

        $veiculo->delete();

        return redirect('/veiculo');

    }

}