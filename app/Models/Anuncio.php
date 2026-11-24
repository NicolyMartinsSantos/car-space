<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AnuncioFoto;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Anuncio extends Model implements Auditable
{
    use AuditableTrait;

    public const MAX_FOTOS = 6;

    protected $fillable = [
        'titulo',
        'descricao',
        'endereco',
        'cidade',
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
     * Garante a remoção das fotografias armazenadas no Storage ao excluir um anúncio.
     */
    protected static function booted(): void
    {
        static::deleting(function (Anuncio $anuncio) {
            $fotos = $anuncio->fotos()->get();

            foreach ($fotos as $foto) {
                if (!empty($foto->caminho) && Storage::disk('public')->exists($foto->caminho)) {
                    Storage::disk('public')->delete($foto->caminho);
                }
            }

            $diretorio = 'anuncios/' . $anuncio->id;
            if (Storage::disk('public')->exists($diretorio)) {
                Storage::disk('public')->deleteDirectory($diretorio);
            }
        });
    }

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

    // anuncio pertence a um usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fotografias cadastradas para o anúncio, ordenadas pela ordem definida.
     */
    public function fotos()
    {
        return $this->hasMany(AnuncioFoto::class)->orderBy('ordem')->orderBy('id');
    }

    /**
     * Fotografia principal (capa) do anúncio.
     */
    public function fotoCapa()
    {
        return $this->hasOne(AnuncioFoto::class)->orderBy('ordem')->orderBy('id');
    }
}
