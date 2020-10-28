<?php

namespace old;

use App\Artist;

class ArtistsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'artistas';
    protected $defaultOrder = 'id_artista';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $artist = new Artist();
        $artist->name = $result->nombre;
        $artist->webName = $result->nombre_web ?: $result->nombre;
        $artist->ticketName = $result->nombre_entrada ?: $result->nombre;
        $artist->oldId = $result->id_artista;
        $artist->save();
    }
}
