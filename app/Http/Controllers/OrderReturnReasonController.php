<?php

namespace App\Http\Controllers;

use App\OrderReturn;
use App\OrderReturnReason;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class OrderReturnReasonController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * OrderReturnReasonController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(OrderReturn::class);
        $this->configureCRUD([
            'modelClass' => OrderReturnReason::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => OrderReturnReason::class,
            'dataGridTitle' => 'Motivos de devolución',
        ]);
    }
}
