<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Anuncio extends Model implements Auditable
{
     use AuditableTrait;

    protected $fillable = [

        'titulo',

        'descricao',

        'endereco',

        'preco',

        'telefone',

        'quantidade_vagas',

        'categoria_vaga',

        'horario_inicio',

        'horario_fim',

        'user_id',

    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function user()
    {
    return $this->belongsTo(User::class);
    }

}