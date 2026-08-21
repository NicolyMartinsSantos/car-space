<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function buscar(Request $request)
    {
        $cep = preg_replace('/[^0-9]/', '', $request->input('cep'));

        if (strlen($cep) !== 8) {
            return response()->json(['erro' => 'CEP inválido'], 400);
        }

        try {
            // withoutVerifying() evita que o PHP trave no SSL do Windows
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get("https://viacep.com.br/ws/{$cep}/json/");

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['erro' => 'Falha na conexão'], 500);
        }
    }
}