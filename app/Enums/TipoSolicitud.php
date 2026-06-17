<?php

namespace App\Enums;

enum TipoSolicitud: string
{
    case Peticion = 'peticion';
    case Queja = 'queja';
    case Reclamo = 'reclamo';
    case Sugerencia = 'sugerencia';
    case Denuncia = 'denuncia';
    case Felicitacion = 'felicitacion';

    public function label(): string
    {
        return match ($this) {
            self::Peticion     => 'Petición',
            self::Queja        => 'Queja',
            self::Reclamo      => 'Reclamo',
            self::Sugerencia   => 'Sugerencia',
            self::Denuncia     => 'Denuncia',
            self::Felicitacion => 'Felicitación',
        };
    }

    public function descripcion(): string
    {
        return match ($this) {
            self::Peticion     => 'Solicitas información, un trámite o una actuación de la entidad.',
            self::Queja        => 'Manifiestas inconformidad por la conducta de un funcionario.',
            self::Reclamo      => 'Reportas una falla o deficiencia en la prestación del servicio.',
            self::Sugerencia   => 'Propones una mejora para el servicio de alumbrado.',
            self::Denuncia     => 'Pones en conocimiento un hecho irregular (daño, vandalismo, hurto).',
            self::Felicitacion => 'Reconoces una buena gestión o atención.',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Peticion     => 'heroicon-o-document-text',
            self::Queja        => 'heroicon-o-hand-raised',
            self::Reclamo      => 'heroicon-o-exclamation-triangle',
            self::Sugerencia   => 'heroicon-o-light-bulb',
            self::Denuncia     => 'heroicon-o-megaphone',
            self::Felicitacion => 'heroicon-o-hand-thumb-up',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Peticion     => 'info',
            self::Queja        => 'warning',
            self::Reclamo      => 'danger',
            self::Sugerencia   => 'success',
            self::Denuncia     => 'gray',
            self::Felicitacion => 'success',
        };
    }

    /** Días hábiles de plazo legal para este tipo (null = sin plazo). */
    public function diasHabiles(): ?int
    {
        return config('pqrs.sla_dias_habiles.' . $this->value);
    }

    public static function opciones(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $e) => [$e->value => $e->label()])->all();
    }
}
