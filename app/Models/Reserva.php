<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{

    protected $fillable = [

        'data',

        'horario',

        'status',

        'veiculo_id',

        'anuncio_id',

    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function anuncio()
    {
        return $this->belongsTo(Anuncio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}