<?php

namespace old;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Log;
use Ramsey\Uuid\Uuid;

class SeatsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'localidades';
    protected $defaultOrder = 'id_recinto';
    protected $maxInserts = 5000;

    /** @var Collection */
    private $sectors;

    public function prepareRecords(): void
    {
        $this->sectors = DB::table('Sector')
            ->select('Sector.id', 'Sector.oldId as SectorOldId', 'Area.oldId as AreaOldId', 'Space.oldId as SpaceOldId')
            ->leftJoin('Area', 'Sector.areaId', '=', 'Area.id')
            ->leftJoin('Space', 'Space.id', '=', 'Area.spaceId')
            ->get();
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        $arraySectors = [];
        $this->sectors->each(static function ($i) use (&$arraySectors) {
            $arraySectors[$i->SectorOldId . $i->AreaOldId . $i->SpaceOldId] = $i->id;
        });

        $seatsToCreate = [];
        $timestamp = Carbon::now()->toISOString();
        $i = 0;
        $errors = 0;
        $seatKeys = [
            "id",
            "sectorId",
            "createdAt",
            "updatedAt",
            "row",
            "column",
            "rowName",
            "number",
            "isForDisabled",
            "reducedVisibility",
            "status",
            "observations",
        ];
        foreach ($results as $result) {
            if (isset($arraySectors[$result->id_zona . $result->id_area . $result->id_aforo])) {
                $seatRow = ($result->numerada) ? $result->coord_y : null;
                $seatColumn = ($result->numerada) ? $result->coord_x : null;
                $seatRowName = ($result->numerada) ? $result->fila : null;
                $seatNumber = ($result->numerada) ? $result->butaca : null;
                $status = (!$result->a_la_venta) ? 'locked' : 'enabled';
                $seat = [
                    Uuid::uuid4()->toString(),
                    $arraySectors[$result->id_zona . $result->id_area . $result->id_aforo],
                    $timestamp,
                    $timestamp,
                    $seatRow,
                    $seatColumn,
                    $seatRowName,
                    $seatNumber,
                    ($result->accesible_discapacitados) ? 1 : 0,
                    ($result->visibilidad_reducida) ? 1 : 0,
                    $status,
                    implode(' ,', [$result->butaca, $result->num_entrada])
                ];
                $seatsToCreate[] = $seat;
            } else {
                Log::warning("Resultado con id $i erróneo. Zona: $result->id_zona Área: $result->id_area Aforo: $result->id_aforo");
                $errors++;
            }
            $i++;
        }
        echo "Errores: $errors\n";
        $this->massiveInsertToCSV('Seat', $seatKeys, $seatsToCreate);
    }

    public function run(): void
    {
        $this->massiveImportUnchunked();
    }
}
