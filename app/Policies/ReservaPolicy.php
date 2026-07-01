<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservaPolicy
{
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    
    public function view(User $user, Reserva $reserva): bool
    {
        return false;
    }

    
    public function create(User $user): bool
    {
        return false;
    }

    
    public function update(User $user, Reserva $reserva): bool
    {
    return $user->id === $reserva->user_id;
    }

    public function delete(User $user, Reserva $reserva): bool
    {
    return $user->id === $reserva->user_id;
    }

   
    public function restore(User $user, Reserva $reserva): bool
    {
        return false;
    }

    
    public function forceDelete(User $user, Reserva $reserva): bool
    {
        return false;
    }
}
