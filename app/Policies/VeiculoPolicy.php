<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Auth\Access\Response;

class VeiculoPolicy
{
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    
    public function view(User $user, Veiculo $veiculo): bool
    {
        return false;
    }

    
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Veiculo $veiculo): bool
    {
    return $user->id === $veiculo->user_id;
    }

    public function delete(User $user, Veiculo $veiculo): bool
    {
    return $user->id === $veiculo->user_id;
    }
    
    public function restore(User $user, Veiculo $veiculo): bool
    {
        return false;
    }

   
    public function forceDelete(User $user, Veiculo $veiculo): bool
    {
        return false;
    }
}
