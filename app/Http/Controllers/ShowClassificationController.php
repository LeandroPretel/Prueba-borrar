<?php

namespace App\Http\Controllers;

use App\ShowClassification;
use App\ShowTemplate;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class ShowClassificationController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * ShowClassificationController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(ShowTemplate::class);
        $this->configureCRUD([
            'modelClass' => ShowClassification::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => ShowClassification::class,
            'dataGridTitle' => 'Clasificaciones de espectáculos',
        ]);
    }
}
