<?php

namespace App\Http\Controllers;

use App\Partner;
use App\PointOfSale;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class PartnerController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * PartnerController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Partner::class);
        $this->configureCRUD([
            'modelClass' => Partner::class,
            'showAppends' => [
                'enterprises',
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => Partner::class,
            'dataGridTitle' => 'Partners',
        ]);
    }

    /**
     * @param Request $request
     * @param Partner $partner
     * @param $saveOrUpdate
     */
    public function savedHook(Request $request, Partner $partner, $saveOrUpdate): void
    {
        if ($saveOrUpdate && $request->filled('enterpriseId')) {
            $partner->enterprises()->sync($request->input('enterpriseId'));
        }
    }
}
