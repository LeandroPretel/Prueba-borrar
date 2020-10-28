<?php

namespace old;

use App\Session;
use App\SessionDoor;

class SessionDoorsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'eventos_puertas';
    protected $defaultOrder = 'id_puerta_evento';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        /** @var Session $session */
        $session = Session::where('oldId', $result->id_evento)->first();
        if ($session) {
            $sessionDoor = new SessionDoor();
            $sessionDoor->session()->associate($session);
            $sessionDoor->place()->associate($session->placeId);
            $sessionDoor->name = $result->nombre;
            $sessionDoor->webName = $result->nombre_web ?: $result->nombre;
            $sessionDoor->ticketName = $result->nombre_entrada ?: $result->nombre;
            $sessionDoor->order = $result->orden;
            // $sessionDoor->observations = $result->orden;
            $sessionDoor->oldId = $result->id_puerta_evento;
            $sessionDoor->save();
        }
    }
}
