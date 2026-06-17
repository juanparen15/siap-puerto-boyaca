<?php

namespace App\Enums;

enum EstadoPqrs: string
{
    case Radicada = 'radicada';
    case EnTramite = 'en_tramite';
    case Respondida = 'respondida';
    case Cerrada = 'cerrada';

    public function label(): string
    {
        return match ($this) {
            self::Radicada   => 'Radicada',
            self::EnTramite  => 'En trámite',
            self::Respondida => 'Respondida',
            self::Cerrada    => 'Cerrada',
        };
    }

    /** Color para badges de Filament. */
    public function color(): string
    {
        return match ($this) {
            self::Radicada   => 'info',
            self::EnTramite  => 'warning',
            self::Respondida => 'success',
            self::Cerrada    => 'gray',
        };
    }

    /** Color hexadecimal (front público). */
    public function hex(): string
    {
        return match ($this) {
            self::Radicada   => '#3366CC',
            self::EnTramite  => '#f59e0b',
            self::Respondida => '#16a34a',
            self::Cerrada    => '#64748b',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Radicada   => 'heroicon-o-inbox-arrow-down',
            self::EnTramite  => 'heroicon-o-arrow-path',
            self::Respondida => 'heroicon-o-chat-bubble-left-right',
            self::Cerrada    => 'heroicon-o-check-circle',
        };
    }

    /** Texto para el ciudadano. */
    public function descripcion(): string
    {
        return match ($this) {
            self::Radicada   => 'Tu solicitud fue recibida y está en cola para ser atendida.',
            self::EnTramite  => 'La Secretaría de Obras Públicas está gestionando tu solicitud.',
            self::Respondida => 'La entidad emitió una respuesta a tu solicitud.',
            self::Cerrada    => 'El caso fue cerrado.',
        };
    }

    /** Estados en los que el SLA deja de correr. */
    public function esFinal(): bool
    {
        return in_array($this, [self::Respondida, self::Cerrada], true);
    }

    /** Transiciones válidas desde este estado. */
    public function siguientesPosibles(): array
    {
        return match ($this) {
            self::Radicada   => [self::EnTramite, self::Respondida, self::Cerrada],
            self::EnTramite  => [self::Respondida, self::Cerrada],
            self::Respondida => [self::Cerrada, self::EnTramite],
            self::Cerrada    => [self::EnTramite],
        };
    }

    public function puedePasarA(self $destino): bool
    {
        return in_array($destino, $this->siguientesPosibles(), true);
    }

    /** Opciones para selects (clave => etiqueta). */
    public static function opciones(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $e) => [$e->value => $e->label()])->all();
    }
}
