<?php

namespace old;

use App\Door;
use App\Place;

class DoorsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'puertas';
    protected $defaultOrder = 'id_puerta';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $place = Place::where('oldId', $result->id_recinto)->first();
        if ($place) {
            $door = new Door();
            $door->place()->associate($place);
            $door->name = $result->nombre;
            $door->webName = $result->nombre_web ?: $result->nombre;
            $door->ticketName = $result->nombre_entrada ?: $result->nombre;
            $door->order = $result->orden;
            // $door->observations = $result->orden;
            $door->oldId = $result->id_puerta;
            $door->save();
        }
    }
}
