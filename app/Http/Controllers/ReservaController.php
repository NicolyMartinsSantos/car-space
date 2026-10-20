<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reserva;
use App\Models\Veiculo;
use App\Models\Anuncio;

class ReservaController extends Controller
{
    public function create(Request $request)
    {
        $anuncioId = $request->query('anuncio_id', old('anuncio_id'));

        if (!$anuncioId) {
            return redirect('/')->with('error', 'Por favor, selecione uma garagem para solicitar a reserva.');
        }

        $anuncio = Anuncio::find($anuncioId);

        if (!$anuncio) {
            return redirect('/')->with('error', 'A garagem selecionada não foi encontrada.');
        }

        if ($anuncio->user_id == auth()->id()) {
            return redirect('/')->with('error', 'Você não pode solicitar reserva na sua própria vaga de garagem.');
        }

        $veiculos = Veiculo::where('user_id', auth()->id())->get();

        return view('reserva.create', compact('veiculos', 'anuncio'));
    }

    public function store(Request $request)
    {
        $anuncio = Anuncio::find($request->anuncio_id);

        if (!$anuncio) {
            return redirect('/')->with('error', 'A garagem selecionada não foi encontrada.');
        }

        if ($anuncio->user_id == auth()->id()) {
            return back()->withErrors(['anuncio_id' => 'Você não pode reservar a sua própria vaga.'])->withInput();
        }

        $veiculo = Veiculo::find($request->veiculo_id);

        if (!$veiculo || $veiculo->user_id != auth()->id()) {
            return back()->withErrors(['veiculo_id' => 'Por favor, selecione um veículo válido cadastrado em sua conta.'])->withInput();
        }

        $reserva = new Reserva();

        $reserva->data = $request->data;

        $reserva->horario = $request->horario;

        $reserva->status = 'Pendente';

        $reserva->veiculo_id = $request->veiculo_id;

        $reserva->anuncio_id = $request->anuncio_id;

        $reserva->user_id = auth()->id();

        $reserva->save();

        return redirect('/reserva')->with('success', 'Solicitação de reserva enviada com sucesso!');
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
        $reserva = Reserva::findOrFail($id);

        $this->authorize('update', $reserva);

        $reserva->data = $request->data;

        $reserva->horario = $request->horario;

        $reserva->veiculo_id = $request->veiculo_id;

        $reserva->anuncio_id = $request->anuncio_id;

        $reserva->save();

        return redirect('/reserva');
    }

    public function aceitar($id)
    {
        $reserva = Reserva::findOrFail($id);

        $this->authorize('responderSolicitacao', $reserva);

        if ($reserva->status !== 'Pendente') {
            return redirect()->back()->with('error', 'Esta solicitação já foi respondida anteriormente.');
        }

        $reserva->status = 'Confirmada';
        $reserva->save();

        return redirect()->back()->with('success', 'Solicitação de reserva aceita com sucesso!');
    }

    public function recusar($id)
    {
        $reserva = Reserva::findOrFail($id);

        $this->authorize('responderSolicitacao', $reserva);

        if ($reserva->status !== 'Pendente') {
            return redirect()->back()->with('error', 'Esta solicitação já foi respondida anteriormente.');
        }

        $reserva->status = 'Recusada';
        $reserva->save();

        return redirect()->back()->with('success', 'Solicitação de reserva recusada.');
    }

    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);

        $this->authorize('delete', $reserva);

        if (!in_array($reserva->status, ['Pendente', 'Confirmada'])) {
            return redirect()->back()->with('error', 'Esta reserva não pode ser cancelada pois já foi finalizada.');
        }

        $reserva->status = 'Cancelada';
        $reserva->save();

        return redirect()->back()->with('success', 'Reserva cancelada com sucesso.');
    }


    public function delete($id)
    {

        $reserva = Reserva::find($id);

        $reserva->delete();

        return redirect('/reserva');

    }

}