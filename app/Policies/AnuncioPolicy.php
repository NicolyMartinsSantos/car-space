<?php

namespace App\Policies;

use App\Models\Anuncio;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnuncioPolicy
{
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    
    public function view(User $user, Anuncio $anuncio): bool
    {
        return false;
    }

    
    public function create(User $user): bool
    {
        return false;
    }


    public function update(User $user, Anuncio $anuncio): bool
    {
    return $user->id === $anuncio->user_id;
    }

    
    public function delete(User $user, Anuncio $anuncio): bool
    {
    return $user->id === $anuncio->user_id;
    }

    
    public function restore(User $user, Anuncio $anuncio): bool
    {
        return false;
    }

    
    public function forceDelete(User $user, Anuncio $anuncio): bool
    {
        return false;
    }
}
