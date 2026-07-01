<?php

namespace App\Services;

use App\Repositories\AnuncioRepository;

class AnuncioService
{
    protected $repository;

    public function __construct(AnuncioRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $dados)
    {
        return $this->repository->create($dados);
    }
}
