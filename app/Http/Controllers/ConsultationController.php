<?php

namespace App\Http\Controllers;

use App\Consultation;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class ConsultationController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * ConsultationController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Consultation::class);
        $this->configureCRUD([
            'modelClass' => Consultation::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Consultation::class,
            'dataGridTitle' => 'Consultas',
        ]);
    }
}
