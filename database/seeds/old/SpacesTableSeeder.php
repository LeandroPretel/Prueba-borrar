<?php

namespace old;

use App\Place;
use App\Space;
use Savitar\Files\SavitarFile;

class SpacesTableSeeder extends OldTableSeeder
{
    protected $tableName = 'aforos';
    protected $defaultOrder = 'id_aforo';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $place = Place::where('oldId', $result->id_recinto)->first();
        if ($place) {
            $space = new Space();
            $space->place()->associate($place);
            $space->name = $result->nombre;
            $space->webName = $result->nombre;
            $space->ticketName = $result->nombre;
            $space->denomination = $result->nombre_escenario;
            $space->maximumCapacity = $result->plazasenventa;
            $space->oldId = $result->id_aforo;
            $space->save();

            $savitarFile = new SavitarFile();
            $savitarFile->name = $result->imagesource;
            $savitarFile->url = 'https://www.redentradas.com/img/aforos/' . $result->imagesource;
            $savitarFile->path = 'https://www.redentradas.com/img/aforos/' . $result->imagesource;
            $savitarFile->extension = 'png';
            $savitarFile->category = 'mainImage';
            $space->files()->save($savitarFile);
        }
    }
}
