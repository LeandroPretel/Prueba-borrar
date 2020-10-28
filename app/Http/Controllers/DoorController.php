<?php

namespace App\Http\Controllers;

use App\Door;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class DoorController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * DoorController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Door::class);
        $this->configureCRUD([
            'modelClass' => Door::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Door::class,
            'dataGridTitle' => 'Listado de puertas',
        ]);
    }
}
