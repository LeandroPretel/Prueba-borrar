<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class PlaceController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * PlaceController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Place::class);
        $this->configureCRUD([
            'modelClass' => Place::class,
            'showAppends' => ['files', 'province', 'country', 'pointsOfSale', 'doors'],
        ]);
        $this->configureDataGrid([
            'modelClass' => Place::class,
            'dataGridTitle' => 'Listado de recintos',
        ]);
    }

    /**
     * @param Request $request
     * @param Place $originalPlace
     * @param Place $replicatedPlace
     */
    protected function replicatedHook(Request $request, Place $originalPlace, Place $replicatedPlace): void
    {
        foreach ($originalPlace->spaces as $originalSpace) {
            $replicatedSpace = $originalSpace->replicate();
            $replicatedSpace->place()->associate($replicatedPlace);
            $replicatedSpace->save();

            // Duplicate space files
            foreach ($originalSpace->files as $file) {
                $replicatedFile = $file->replicate();
                $replicatedSpace->files()->save($replicatedFile);
            }

            // Duplicate areas
            foreach ($originalSpace->areas as $area) {
                $replicatedArea = $area->replicate();
                $replicatedArea->space()->associate($replicatedSpace);
                $replicatedArea->save();

                // Duplicate area files
                foreach ($area->files as $file) {
                    $replicatedFile = $file->replicate();
                    $replicatedArea->files()->save($replicatedFile);
                }

                // Duplicate area sectors
                foreach ($area->sectors as $sector) {
                    $replicatedSector = $sector->replicate();
                    $replicatedSector->area()->associate($replicatedArea);
                    $replicatedSector->save();

                    // Duplicate sector seats
                    foreach ($sector->seats as $seat) {
                        $replicatedSeat = $seat->replicate();
                        $seat->sector()->associate($replicatedSector);
                        $replicatedSeat->save();
                    }
                }
            }
        }
    }
}
