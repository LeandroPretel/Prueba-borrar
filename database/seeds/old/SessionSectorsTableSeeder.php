<?php

namespace old;

use Carbon\Carbon;
use DB;
use Ramsey\Uuid\Uuid;

class SessionSectorsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'eventos_zonas';
    protected $defaultOrder = 'id_zona_evento';

    private $sessions;
    private $sessionAreas;

    public function prepareRecords(): void
    {
        $this->sessions = [];
        $sessionResults = DB::table('Session')->select('id', 'oldId', 'spaceId')->get();
        foreach ($sessionResults as $result) {
            $this->sessions[$result->oldId] = ["id" => $result->id, "spaceId" => $result->spaceId];
        }
        $sessionResults = null;

        $this->sessionAreas = [];
        $sessionAreasResult = DB::table('SessionArea')->select('id', 'oldId', 'sessionId', 'spaceId')->get();
        foreach ($sessionAreasResult as $result) {
            $this->sessionAreas[$result->oldId . $result->sessionId . $result->spaceId] = $result->id;
        }
        $sessionAreasResult = null;
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        $sessionsSectorsToCreate = [];
        $timestamp = Carbon::now()->toISOString();
        foreach ($results as $result) {
            $sessionId = (isset($this->sessions[$result->id_evento])) ? $this->sessions[$result->id_evento]["id"] : null;
            $spaceId = (isset($this->sessions[$result->id_evento])) ? $this->sessions[$result->id_evento]["spaceId"] : null;
            if ($sessionId) {
                $sessionAreaId = $this->sessionAreas[$result->id_area_evento . $sessionId . $spaceId] ?? null;
                if ($sessionAreaId) {
                    $sessionSectorId = Uuid::uuid4()->toString();
                    $sessionSector = [
                        "id" => $sessionSectorId,
                        "createdAt" => $timestamp,
                        "updatedAt" => $timestamp,
                        "sessionId" => $sessionId,
                        "sessionAreaId" => $sessionAreaId,
                        "name" => $result->nombre,
                        "webName" => $result->nombre_web ?: $result->nombre,
                        "ticketName" => $result->nombre_entrada ?: $result->nombre,
                        "isNumbered" => $result->numerada,
                        "totalSeats" => $result->plazasenventa,
                        "rows" => $result->alto,
                        "columns" => $result->ancho,
                        "disabledAccess" => $result->accesible_discapacitados,
                        "reducedVisibility" => $result->visibilidad_reducida,
                        "stageLocation" => null,
                        "oldId" => $result->id_zona_evento,
                    ];

                    if ($result->coord_x && $result->coord_y) {
                        $sessionSector["centers"] = '[[' . $result->coord_x * 4 / 5 . ',' . $result->coord_y * 4 / 5 . ']]';
                    } else {
                        $sessionSector["centers"] = '[]';
                    }

                    if ($result->mapcoords) {
                        $sessionSector["points"] = '[[';
                        $points = explode(',', $result->mapcoords);
                        $size = count($points);
                        for ($i = 0; $i < $size; $i += 2) {
                            if ($points[$i] && $points[$i + 1]) {
                                $sessionSector["points"] .= '[' . $points[$i] * 4 / 5 . ',' . $points[$i + 1] * 4 / 5 . ']';
                                if ($i + 2 !== $size) {
                                    $sessionSector["points"] .= ',';
                                }
                            }
                        }
                        $sessionSector["points"] .= ']]';
                    } else {
                        $sessionSector["points"] = '[]';
                    }
                    $sessionsSectorsToCreate[] = $sessionSector;
                } else {
                    dd($result, $sessionId, $spaceId);
                }
            } else {
                dd($result);
            }
        }

        $this->chunkInsert('SessionSector', 1000, $sessionsSectorsToCreate);
    }

    public function run(): void
    {
        $this->massiveImport();
    }
}
