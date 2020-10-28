<?php

namespace App\Http\Controllers;

use App\TicketSeason;
use App\TicketSeasonGroup;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class TicketSeasonGroupController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * TicketSeasonGroupController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(TicketSeason::class);
        $this->configureCRUD([
            'modelClass' => TicketSeasonGroup::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => TicketSeasonGroup::class,
            'dataGridTitle' => 'Grupos de abonados',
        ]);
    }
}
