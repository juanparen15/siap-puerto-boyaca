<?php

namespace App\Filament\Imports;

use App\Models\InfraestructuraElemento;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\ValidationException;

class InfraestructuraElementoImporter extends Importer
{
    protected static ?string $model = InfraestructuraElemento::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tipo')
                ->label('tipo_de_elemento')
                ->guess(['tipo_de_elemento', 'tipo de elemento', 'tipo'])
                ->rules(['required'])
                ->fillRecordUsing(function ($record, $value) {
                    $allowed = ['luminaria', 'poste', 'reflector', 'sendero_peatonal', 'campo_deportivo', 'luminaria_parque'];
                    $mapped = strtolower(str_replace(' ', '_', trim($value)));

                    if (! in_array($mapped, $allowed)) {
                        throw ValidationException::withMessages([
                            'tipo' => "Tipo de elemento inválido: \"{$value}\". "
                                . 'Valores permitidos: ' . implode(', ', $allowed) . '.',
                        ]);
                    }

                    $record->tipo = $mapped;
                }),
            ImportColumn::make('rotulo')
                ->label('referencia_del_rotulo')
                ->guess(['referencia_del_rotulo', 'rotulo', 'rótulo']),
            ImportColumn::make('marca')
                ->guess(['marca']),
            ImportColumn::make('tecnologia')
                ->label('tipo_de_tecnologia')
                ->guess(['tipo_de_tecnologia', 'tecnologia', 'tecnología']),
            ImportColumn::make('potencia_w')
                ->label('potencia_w')
                ->guess(['potencia_w', 'potencia'])
                ->integer(),
            ImportColumn::make('estado')
                ->label('estado_actual')
                ->guess(['estado_actual', 'estado'])
                ->fillRecordUsing(fn ($record, $value) => $record->estado = match(strtoupper(trim((string) $value))) {
                    'OPERATIVA' => 'operativa',
                    'NO OPERATIVA' => 'no_operativa',
                    'DESINSTALADA' => 'desinstalada',
                    default => 'operativa',
                }),
            ImportColumn::make('tipo_poste')
                ->label('tipo_de_poste')
                ->guess(['tipo_de_poste', 'tipo_poste']),
            ImportColumn::make('altura_poste_m')
                ->label('altura_del_poste_m')
                ->guess(['altura_del_poste_m', 'altura_poste_m'])
                ->numeric(),
            ImportColumn::make('carga_rotura_kgf')
                ->label('carga_de_rotura_kgf')
                ->guess(['carga_de_rotura_kgf', 'carga_rotura_kgf'])
                ->integer(),
            ImportColumn::make('clasificacion')
                ->guess(['clasificacion', 'clasificación'])
                ->fillRecordUsing(fn ($record, $value) => $record->clasificacion = match(strtoupper(trim((string) $value))) {
                    'CASCO URBANO' => 'casco_urbano',
                    'PUERTO SERVIEZ' => 'puerto_serviez',
                    default => 'casco_urbano',
                }),
            ImportColumn::make('descripcion')
                ->guess(['descripcion', 'descripción']),
            ImportColumn::make('observaciones')
                ->label('observaciones_mtto')
                ->guess(['observaciones_mtto', 'observaciones']),
            ImportColumn::make('latitud')
                ->label('y')
                ->guess(['y', 'latitud', 'lat'])
                ->numeric()
                ->rules(['nullable', 'numeric', 'between:-4,13']),
            ImportColumn::make('longitud')
                ->label('x')
                ->guess(['x', 'longitud', 'lon', 'lng'])
                ->numeric()
                ->rules(['nullable', 'numeric', 'between:-82,-66']),
            ImportColumn::make('fecha_levantamiento')
                ->label('fecha_de_levantamiento')
                ->guess(['fecha_de_levantamiento', 'fecha_levantamiento']),
            ImportColumn::make('globalid')
                ->guess(['globalid', 'GlobalID', 'objectid'])
                ->sensitive(),
        ];
    }

    public function resolveRecord(): ?InfraestructuraElemento
    {
        // 1) Clave principal de deduplicación: globalid
        $globalid = $this->data['globalid'] ?? null;
        if (! empty($globalid)) {
            return InfraestructuraElemento::firstOrNew(['globalid' => $globalid]);
        }

        // 2) Respaldo: si no hay globalid, evitar duplicados por rótulo
        $rotulo = $this->data['rotulo'] ?? null;
        if (! empty($rotulo)) {
            return InfraestructuraElemento::firstOrNew(['rotulo' => $rotulo]);
        }

        // 3) Sin identificadores: crear nuevo
        return new InfraestructuraElemento();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Importación completada: ' . number_format($import->successful_rows) . ' registros procesados.';
    }
}
