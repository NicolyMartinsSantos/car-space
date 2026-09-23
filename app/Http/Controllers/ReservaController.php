<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reserva;
use App\Models\Veiculo;
use App\Models\Anuncio;

class ReservaController extends Controller
{
    public function create()
    {
        $veiculos = Veiculo::where('user_id', auth()->id())->get();

        $anuncios = Anuncio::where('user_id', '!=', auth()->id())->get();

        return view('reserva.create', compact('veiculos', 'anuncios'));
    }


public function store(Request $request)
{
    $anuncio = Anuncio::find($request->anuncio_id);

    if ($anuncio && $anuncio->user_id == auth()->id()) {
        return back()->withErrors(['anuncio_id' => 'Você não pode reservar a sua própria vaga.']);
    }

    $reserva = new Reserva();

    $reserva->data = $request->data;

    $reserva->horario = $request->horario;

    $reserva->status = 'Pendente';

    $reserva->veiculo_id = $request->veiculo_id;

    $reserva->anuncio_id = $request->anuncio_id;

    $reserva->user_id = auth()->id();

    $reserva->save();

    return redirect('/reserva');
}


    public function index()
{

    $data = Reserva::where('user_id', auth()->id())->get();

    return view('reserva.index', compact('data'));

}


    public function edit($id)
{
    $reserva = Reserva::find($id);

    $this->authorize('update', $reserva);

    $veiculos = Veiculo::all();

    $anuncios = Anuncio::all();

    return view(
        'reserva.edit',
        compact('reserva', 'veiculos', 'anuncios')
    );
}


    public function update(Request $request, $id)
    {

        $reserva = Reserva::find($id);

        $this->authorize('delete', $reserva);

        $reserva->data = $request->data;

        $reserva->horario = $request->horario;

        $reserva->status = $request->status;

        $reserva->veiculo_id = $request->veiculo_id;

        $reserva->anuncio_id = $request->anuncio_id;

        $reserva->save();

        return redirect('/reserva');

    }


    public function delete($id)
    {

        $reserva = Reserva::find($id);

        $reserva->delete();

        return redirect('/reserva');

    }

}