<?php

namespace App\Http\Controllers;

use App\Enterprise;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class EnterpriseController extends Controller
{
    use CRUD;
    use DataGrid;
    use Authorization;

    /**
     * EnterpriseController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Enterprise::class);

        $showAppends = ['files'];
        if (config('savitar_auth.zones.enabled')) {
            $showAppends[] = 'province';
            $showAppends[] = 'country';
        }

        $this->configureCRUD([
            'modelClass' => Enterprise::class,
            'showAppends' => $showAppends,
        ]);
        $this->configureDataGrid([
            'modelClass' => Enterprise::class,
            'dataGridTitle' => 'Empresas',
        ]);
    }

    /**
     * Check NIF
     *
     * Checks if an existing enterprise have the nif.
     *
     * @urlParam nif string required The nif to check. Example: 87428906H
     * @response {
     *  "check": true,
     * }
     *
     * @param string $nif
     * @return JsonResponse
     */
    public function checkNif(string $nif): JsonResponse
    {
        $enterprise = Enterprise::where('nif', $nif)->first();
        $enterprise ? $check = true : $check = false;

        return response()->json(['check' => $check]);
    }
}
