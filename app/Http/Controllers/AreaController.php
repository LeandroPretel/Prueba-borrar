<?php

namespace App\Http\Controllers;

use App\Area;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class AreaController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * AreaController constructor.
     */
    public function __construct()
    {
        $this->configureCRUD([
            'modelClass' => Area::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Area::class,
            'dataGridTitle' => 'Áreas',
        ]);
    }
}
