<?php

namespace App\Console\Commands;

use App\Models\InfraestructuraElemento;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportarInventarioCommand extends Command
{
    protected $signature = 'siap:importar-inventario {archivo : Ruta al archivo CSV Survey123}';
    protected $description = 'Importa el inventario de infraestructura desde un CSV de Survey123/ArcGIS';

    public function handle(): int
    {
        $path = $this->argument('archivo');

        if (!file_exists($path)) {
            $this->error("Archivo no encontrado: {$path}");
            return self::FAILURE;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $inserted = 0;
        $updated = 0;
        $errors = 0;

        $this->info("Procesando CSV...");
        $bar = $this->output->createProgressBar();

        foreach ($csv->getRecords() as $row) {
            try {
                $globalid = $row['globalid'] ?? null;
                if (empty($globalid)) {
                    $errors++;
                    continue;
                }

                if (empty($row['y'] ?? '') || empty($row['x'] ?? '')) {
                    $this->warn("\nCoordenadas vacías para globalid={$globalid}. Saltando.");
                    $errors++;
                    continue;
                }
                $lat = (float) $row['y'];
                $lng = (float) $row['x'];

                // Validate coordinate ranges (Colombian bounds: lat -4 to 13, lng -82 to -66)
                if ($lat < -4 || $lat > 13 || $lng < -82 || $lng > -66) {
                    $this->warn("\nCoordenadas fuera de rango para globalid={$globalid}. Saltando.");
                    $errors++;
                    continue;
                }

                $exists = InfraestructuraElemento::where('globalid', $globalid)->exists();

                InfraestructuraElemento::updateOrCreate(
                    ['globalid' => $globalid],
                    [
                        'tipo' => strtolower(str_replace(' ', '_', $row['tipo_de_elemento'] ?? 'luminaria')),
                        'rotulo' => ($row['referencia_del_rotulo'] ?? '') ?: null,
                        'marca' => ($row['marca'] ?? '') ?: null,
                        'tecnologia' => ($row['tipo_de_tecnologia'] ?? '') ?: null,
                        'potencia_w' => is_numeric($row['potencia_w'] ?? '') ? (int)$row['potencia_w'] : null,
                        'estado' => match(strtoupper(trim($row['estado_actual'] ?? ''))) {
                            'OPERATIVA' => 'operativa',
                            'NO OPERATIVA' => 'no_operativa',
                            'DESINSTALADA' => 'desinstalada',
                            default => 'operativa',
                        },
                        'clasificacion' => match(strtoupper(trim($row['clasificacion'] ?? ''))) {
                            'CASCO URBANO' => 'casco_urbano',
                            'PUERTO SERVIEZ' => 'puerto_serviez',
                            default => 'casco_urbano',
                        },
                        'latitud' => $lat,
                        'longitud' => $lng,
                        'tipo_poste' => ($row['tipo_de_poste'] ?? '') ?: null,
                        'altura_poste_m' => is_numeric($row['altura_del_poste_m'] ?? '') ? $row['altura_del_poste_m'] : null,
                        'carga_rotura_kgf' => is_numeric($row['carga_de_rotura_kgf'] ?? '') ? (int)$row['carga_de_rotura_kgf'] : null,
                        'descripcion' => ($row['descripcion'] ?? '') ?: null,
                        'observaciones' => ($row['observaciones_mtto'] ?? '') ?: null,
                        'fecha_levantamiento' => (function () use ($row) {
                            try {
                                return !empty($row['fecha_de_levantamiento'])
                                    ? \Carbon\Carbon::parse($row['fecha_de_levantamiento'])->toDateString()
                                    : null;
                            } catch (\Exception $e) {
                                return null;
                            }
                        })(),
                    ]
                );

                $exists ? $updated++ : $inserted++;
                $bar->advance();
            } catch (\Throwable $e) {
                $gid = $row['globalid'] ?? 'N/A';
                $this->warn("\nError en fila globalid={$gid}: {$e->getMessage()}");
                $errors++;
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Importación completada: {$inserted} insertados, {$updated} actualizados, {$errors} errores.");

        return self::SUCCESS;
    }
}
