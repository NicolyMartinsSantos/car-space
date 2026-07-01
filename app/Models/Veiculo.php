<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{

    protected $fillable = [

        'placa',

        'modelo',

        'cor',

        'ano',

        'categoria',

    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

}