<?php

namespace App\Http\Controllers;

use App\ShowTemplate;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class ShowTemplateController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * ShowTemplateController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(ShowTemplate::class);
        $this->configureCRUD([
            'modelClass' => ShowTemplate::class,
            'showAppends' => ['files', 'showCategories', 'artists', 'showClassification'],
        ]);
        $this->configureDataGrid([
            'modelClass' => ShowTemplate::class,
            'dataGridTitle' => 'Espectáculos',
        ]);
    }
}
