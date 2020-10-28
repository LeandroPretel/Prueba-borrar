<?php

namespace App\Http\Controllers;

use App\Area;
use App\Place;
use App\Sector;
use App\Space;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class SectorController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * SectorController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Place::class);
        $this->configureCRUD([
            'modelClass' => Sector::class,
            'showAppends' => [
                'doors',
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => Sector::class,
            'dataGridTitle' => 'Sectores',
        ]);
    }

    /**
     * Retrieve
     *
     * Retrieves the details of an existing resource from the database.
     *
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        /** @var Sector $model */
        $model = $this->getModelFromRoute($request, false, true);
        if ($model->isNumbered) {
            $this->showAppends[] = 'seats.doors';
        }
        if ($this->showAppends) {
            $model->loadMissing($this->showAppends);
        }
        return $model;
    }

    /**
     * @param Request $request
     * @param Place $place
     * @param Space $space
     * @param Area $area
     * @param Sector $sector
     * @return JsonResponse
     * @throws Exception
     */
    protected function deleteSeats(Request $request, Place $place, Space $space, Area $area, Sector $sector): JsonResponse
    {
        foreach ($sector->seats as $seat) {
            $seat->delete();
        }
        return response()->json(['Butacas eliminadas.'], 200);
    }
}
