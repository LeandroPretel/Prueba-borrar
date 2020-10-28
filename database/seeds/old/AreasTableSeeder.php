<?php

namespace old;

use App\Area;
use App\Space;
use Savitar\Files\SavitarFile;

class AreasTableSeeder extends OldTableSeeder
{
    protected $tableName = 'areas';
    protected $defaultOrder = 'id_area';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $space = Space::where('oldId', $result->id_aforo)->first();
        if ($space) {
            $area = new Area();
            $area->space()->associate($space);
            $area->name = $result->nombre;
            $area->webName = $result->nombre;
            $area->ticketName = $result->nombre;
            $area->color = $result->color;
            $area->order = $result->orden;
            // $area->observations = $result->orden;
            $area->oldId = $result->id_area;
            $area->save();

            // TODO: RECALCULAR TOTAL DE ASIENTOS
            $savitarFile = new SavitarFile();
            $savitarFile->name = $result->maskedimage;
            $savitarFile->url = 'https://www.redentradas.com/img/eventos/' . $result->maskedimage;
            $savitarFile->path = 'https://www.redentradas.com/img/eventos/' . $result->maskedimage;
            $savitarFile->extension = 'png';
            $savitarFile->category = 'mainImage';
            $area->files()->save($savitarFile);
        }
    }
}
