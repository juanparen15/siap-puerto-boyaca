<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PqrsHistorial extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'pqrs_id', 'usuario_id', 'estado_anterior', 'estado_nuevo', 'observacion',
    ];

    protected $casts = ['created_at' => 'datetime'];

    public function pqrs(): BelongsTo
    {
        return $this->belongsTo(Pqrs::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
