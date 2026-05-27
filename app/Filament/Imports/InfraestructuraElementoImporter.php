<?php

namespace App\Filament\Imports;

use App\Models\InfraestructuraElemento;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class InfraestructuraElementoImporter extends Importer
{
    protected static ?string $model = InfraestructuraElemento::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tipo')
                ->label('tipo_de_elemento')
                ->rules(['required'])
                ->fillRecordUsing(fn ($record, $value) => $record->tipo = strtolower(str_replace(' ', '_', $value))),
            ImportColumn::make('rotulo')
                ->label('referencia_del_rotulo'),
            ImportColumn::make('marca'),
            ImportColumn::make('tecnologia')
                ->label('tipo_de_tecnologia'),
            ImportColumn::make('potencia_w')
                ->label('potencia_w')
                ->integer(),
            ImportColumn::make('estado')
                ->label('estado_actual')
                ->fillRecordUsing(fn ($record, $value) => $record->estado = match(strtoupper(trim($value))) {
                    'OPERATIVA' => 'operativa',
                    'NO OPERATIVA' => 'no_operativa',
                    'DESINSTALADA' => 'desinstalada',
                    default => 'operativa',
                }),
            ImportColumn::make('tipo_poste')
                ->label('tipo_de_poste'),
            ImportColumn::make('altura_poste_m')
                ->label('altura_del_poste_m')
                ->numeric(),
            ImportColumn::make('carga_rotura_kgf')
                ->label('carga_de_rotura_kgf')
                ->integer(),
            ImportColumn::make('clasificacion')
                ->fillRecordUsing(fn ($record, $value) => $record->clasificacion = match(strtoupper(trim($value))) {
                    'CASCO URBANO' => 'casco_urbano',
                    'PUERTO SERVIEZ' => 'puerto_serviez',
                    default => 'casco_urbano',
                }),
            ImportColumn::make('descripcion'),
            ImportColumn::make('observaciones')
                ->label('observaciones_mtto'),
            ImportColumn::make('latitud')
                ->label('y')
                ->numeric()
                ->rules(['numeric', 'between:-4,13']),
            ImportColumn::make('longitud')
                ->label('x')
                ->numeric()
                ->rules(['numeric', 'between:-82,-66']),
            ImportColumn::make('fecha_levantamiento')
                ->label('fecha_de_levantamiento'),
            ImportColumn::make('globalid')
                ->sensitive(),
        ];
    }

    public function resolveRecord(): ?InfraestructuraElemento
    {
        return InfraestructuraElemento::firstOrNew(['globalid' => $this->data['globalid']]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Importación completada: ' . number_format($import->successful_rows) . ' registros procesados.';
    }
}
