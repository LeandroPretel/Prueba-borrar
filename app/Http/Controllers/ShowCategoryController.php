<?php

namespace App\Http\Controllers;

use App\ShowCategory;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class ShowCategoryController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * ShowCategoryController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(ShowCategory::class);

        $this->configureCRUD([
            'modelClass' => ShowCategory::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => ShowCategory::class,
            'dataGridTitle' => 'Categorías de espectáculos',
        ]);
    }
}
