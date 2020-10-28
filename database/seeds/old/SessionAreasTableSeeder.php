<?php

namespace old;

use Carbon\Carbon;
use DB;
use Ramsey\Uuid\Uuid;

class SessionAreasTableSeeder extends OldTableSeeder
{
    protected $tableName = 'eventos_areas';
    protected $defaultOrder = 'id_area_evento';


    private $sessions;

    public function prepareRecords(): void
    {
        $this->sessions = [];
        $sessionResults = DB::table('Session')->select('id', 'oldId', 'spaceId')->get();
        foreach ($sessionResults as $result) {
            $this->sessions[$result->oldId] = ["id" => $result->id, "spaceId" => $result->spaceId];
        }
        $sessionResults = null;
    }

    /**
     * @param $results
     */

    // TODO: RECALCULAR TOTAL DE ASIENTOS
    public function createRecords($results): void
    {
        $sessionAreasToCreate = [];
        $savitarFilesToCreate = [];
        $timestamp = Carbon::now()->toISOString();

        foreach ($results as $result) {
            $sessionAreaId = Uuid::uuid4()->toString();
            $sessionId = $this->sessions[$result->id_evento]["id"];
            $spaceId = $this->sessions[$result->id_evento]["spaceId"];
            $sessionArea = [
                "id" => $sessionAreaId,
                "createdAt" => $timestamp,
                "updatedAt" => $timestamp,
                "sessionId" => $sessionId,
                "spaceId" => $spaceId,
                "name" => $result->nombre,
                "webName" => $result->nombre,
                "ticketName" => $result->nombre,
                "color" => $result->color,
                "totalSeats" => 0,
                "observations" => $result->orden,
                "oldId" => $result->id_area_evento,
            ];
            $sessionAreasToCreate[] = $sessionArea;

            $fileId = Uuid::uuid4()->toString();
            $savitarFile = [
                "id" => $fileId,
                "fileableId" => $sessionAreaId,
                "fileableType" => 'App\SessionArea',
                "name" => $result->maskedimage,
                "path" => 'https://www.redentradas.com/img/aforos/' . $result->maskedimage,
                "url" => 'https://www.redentradas.com/img/aforos/' . $result->maskedimage,
                "category" => "mainImage",
                "extension" => "png",
                "createdAt" => $timestamp,
                "updatedAt" => $timestamp,
            ];

            $savitarFilesToCreate[] = $savitarFile;
        }

        $this->chunkInsert('SessionArea', 1000, $sessionAreasToCreate);
        $this->chunkInsert('File', 1000, $savitarFilesToCreate);
    }

    public function run(): void
    {
        $this->massiveImport();
    }
}
