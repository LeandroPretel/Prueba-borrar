<?php

namespace App\Http\Controllers;

use App\Artist;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class ArtistController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * ArtistController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Artist::class);
        $this->configureCRUD([
            'modelClass' => Artist::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Artist::class,
            'dataGridTitle' => 'Artistas',
        ]);
    }
}
