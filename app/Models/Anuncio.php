<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

        'dias_disponiveis',

        'user_id',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dias_disponiveis' => 'array',
        ];
    }

    /**
     * Intercepta o acesso a dias_disponiveis para fornecer fallback para anúncios legados
     * sem alterar ou sobrescrever os dados persistidos no banco de dados.
     */
    protected function diasDisponiveis(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value)
                ? ['seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom']
                : (is_string($value) ? json_decode($value, true) : $value)
        );
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    //anuncio pertence a um usuario
    public function user()
    {
    return $this->belongsTo(User::class);
    }

}