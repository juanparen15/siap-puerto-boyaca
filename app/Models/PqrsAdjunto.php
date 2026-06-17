<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PqrsAdjunto extends Model
{
    protected $table = 'pqrs_adjuntos';

    protected $fillable = ['pqrs_id', 'ruta', 'nombre_original', 'mime', 'tamano'];

    public function pqrs(): BelongsTo
    {
        return $this->belongsTo(Pqrs::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->ruta);
    }

    public function getEsImagenAttribute(): bool
    {
        return str_starts_with((string) $this->mime, 'image/');
    }
}
