<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AnuncioFoto extends Model
{
    protected $table = 'anuncio_fotos';

    protected $fillable = [
        'anuncio_id',
        'caminho',
        'ordem',
    ];

    protected $appends = ['url'];

    public function anuncio()
    {
        return $this->belongsTo(Anuncio::class);
    }

    /**
     * Retorna a URL pública da fotografia armazenada no disco public.
     * Utiliza asset('storage/...') para funcionar independentemente do host/porta local.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . ltrim($this->caminho, '/'));
    }

    /**
     * Verifica se o arquivo físico existe no Storage public.
     */
    public function arquivoExiste(): bool
    {
        return !empty($this->caminho) && Storage::disk('public')->exists($this->caminho);
    }
}
