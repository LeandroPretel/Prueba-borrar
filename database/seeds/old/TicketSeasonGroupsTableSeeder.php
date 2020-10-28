<?php

namespace old;

use App\TicketSeasonGroup;

class TicketSeasonGroupsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'abonados_grupos';
    protected $defaultOrder = 'id_grupo_abonados';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $ticketSeasonGroup = new TicketSeasonGroup();
        $ticketSeasonGroup->name = $result->nombre;
        $ticketSeasonGroup->oldId = $result->id_grupo_abonados;
        $ticketSeasonGroup->save();
    }
}
