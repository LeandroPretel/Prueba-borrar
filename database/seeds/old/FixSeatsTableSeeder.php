<?php

namespace old;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class FixSeatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $sectorBoundaries = [];
        $sectorsResult = null;
        $seatsResult = DB::table('Seat')->select('id', 'row', 'column', 'sectorId')->whereNotNull('row')->orderBy('sectorId')->orderBy('column', 'ASC')->orderBy('row', 'ASC')->get();

        $seatsToCreate = [];
        $timestamp = Carbon::now()->toISOString();

        // Creamos la primera columna de todas las filas faltantes
        foreach ($seatsResult as $index => $seat) {
            if ($index >= 1) {
                if ($seatsResult[$index - 1]->sectorId === $seat->sectorId || ($seatsResult[$index - 1]->sectorId !== $seat->sectorId && $seat->row !== 1)) {
                    // Caso en la que la primera fila no existe
                    if ($seatsResult[$index - 1]->sectorId !== $seat->sectorId && $seat->row !== 1) {
                        $lastRow = 0;
                    } else {
                        $lastRow = $seatsResult[$index - 1]->row;
                    }

                    // Obtenemos los tamaños máximos de los sectores
                    if (isset($sectorBoundaries[$seat->sectorId])) {
                        $sectorBoundaries[$seat->sectorId] = ($sectorBoundaries[$seat->sectorId] < $seat->column) ? $seat->column : $sectorBoundaries[$seat->sectorId];
                    } else {
                        $sectorBoundaries[$seat->sectorId] = $seat->column;
                    }

                    if ($lastRow + 1 !== $seat->row) {
                        for ($i = $lastRow + 1; $i < $seat->row; $i++) {
                            $newSeat = [
                                "id" => Uuid::uuid4()->toString(),
                                "sectorId" => $seat->sectorId,
                                "createdAt" => $timestamp,
                                "updatedAt" => $timestamp,
                                "row" => $i,
                                "column" => $seat->column,
                                "rowName" => null,
                                "number" => null,
                                "status" => "deleted",
                                "isForDisabled" => false,
                                "reducedVisibility" => false,
                                "observations" => null
                            ];

                            $seatsToCreate[] = $newSeat;
                        }
                    }
                }
            }
        }
        $seatsResult = null;
        sleep(1);
        echo "Insertando " . count($seatsToCreate) . " localidades eliminadas...\n";
        $chunks = array_chunk($seatsToCreate, 5000);
        foreach ($chunks as $chunk) {
            DB::table('Seat')->insert($chunk);
        }

        $seatsResult = DB::table('Seat')->select('id', 'row', 'column', 'sectorId')->whereNotNull('row')->orderBy('sectorId')->orderBy('row', 'ASC')->orderBy('column', 'ASC')->get();

        $seatsToCreate = [];
        $timestamp = Carbon::now()->toISOString();
        foreach ($seatsResult as $index => $seat) {
            // Comprobamos que no sea la primera, que estemos en el mismo sector
            if ($index >= 1 && $seatsResult[$index - 1]->sectorId === $seat->sectorId) {
                if ($seatsResult[$index - 1]->row === $seat->row) {
                    $lastSeat = $seatsResult[$index - 1]->column;
                    if ($lastSeat + 1 !== $seat->column) {
                        for ($i = $lastSeat + 1; $i < $seat->column; $i++) {
                            $newSeat = [
                                "id" => Uuid::uuid4()->toString(),
                                "sectorId" => $seat->sectorId,
                                "createdAt" => $timestamp,
                                "updatedAt" => $timestamp,
                                "row" => $seat->row,
                                "column" => $i,
                                "rowName" => null,
                                "number" => null,
                                "status" => "deleted",
                                "isForDisabled" => false,
                                "reducedVisibility" => false,
                                "observations" => null
                            ];
                            $seatsToCreate[] = $newSeat;
                        }
                    }
                } else {
                    $toSeat = $sectorBoundaries[$seat->sectorId];
                    for ($i = $seatsResult[$index - 1]->column + 1; $i <= $toSeat; $i++) {
                        $newSeat = [
                            "id" => Uuid::uuid4()->toString(),
                            "sectorId" => $seat->sectorId,
                            "createdAt" => $timestamp,
                            "updatedAt" => $timestamp,
                            "row" => $seatsResult[$index - 1]->row,
                            "column" => $i,
                            "rowName" => null,
                            "number" => null,
                            "status" => "deleted",
                            "isForDisabled" => false,
                            "reducedVisibility" => false,
                            "observations" => null
                        ];
                        $seatsToCreate[] = $newSeat;
                    }
                }
            }
        }

        echo "Insertando " . count($seatsToCreate) . " localidades eliminadas...\n";
        $chunks = array_chunk($seatsToCreate, 5000);
        foreach ($chunks as $chunk) {
            DB::table('Seat')->insert($chunk);
        }
    }
}
