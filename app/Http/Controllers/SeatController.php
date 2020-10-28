<?php

namespace App\Http\Controllers;

use App\Place;
use App\Seat;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class SeatController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * SeatController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Place::class);
        $this->configureCRUD([
            "indexAppends" => ['doors'],
            "showAppends" => ['doors'],
            'modelClass' => Seat::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Seat::class,
            'dataGridTitle' => 'Butacas',
        ]);
    }
}
