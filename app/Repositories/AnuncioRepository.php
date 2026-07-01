<?php

namespace App\Repositories;

use App\Models\Anuncio;

class AnuncioRepository
{
    public function create(array $dados)
    {
        return Anuncio::create($dados);
    }
}