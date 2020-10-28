<?php

namespace App\Http\Controllers;

use App\Place;
use App\Space;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Str;

class SpaceController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * SpaceController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Place::class);
        $this->configureCRUD([
            'modelClass' => Space::class,
            'showAppends' => ['files', 'place', 'areas.sectors.seats.doors', 'areas.sectors.doors', 'areas.files'],
        ]);

        $this->configureDataGrid([
            'modelClass' => Space::class,
            'dataGridTitle' => 'Aforos',
        ]);
    }

    /**
     * @param Request $request
     * @param Space $originalSpace
     * @param Space $replicatedSpace
     */
    protected function replicatedHook(Request $request, Space $originalSpace, Space $replicatedSpace): void
    {
        foreach ($originalSpace->files as $file) {
            $replicatedFile = $file->replicate();
            $replicatedSpace->files()->save($replicatedFile);
        }

        // Duplicate areas
        foreach ($originalSpace->areas as $area) {
            $replicatedArea = $area->replicate();
            $replicatedArea->space()->associate($replicatedSpace);
            $replicatedArea->id = Str::uuid()->toString();
            $replicatedArea->saveWithoutEvents();
            // Duplicate area files
            foreach ($area->files as $file) {
                $replicatedFile = $file->replicate();
                $replicatedArea->files()->save($replicatedFile);
            }

            // Duplicate area sectors
            foreach ($area->sectors as $sector) {
                $replicatedSector = $sector->replicate();
                $replicatedSector->area()->associate($replicatedArea);
                $replicatedSector->id = Str::uuid()->toString();
                $replicatedSector->saveWithoutEvents();

                // Duplicate sector seats
                foreach ($sector->seats as $seat) {
                    $replicatedSeat = $seat->replicate();
                    $replicatedSeat->sector()->associate($replicatedSector);
                    $replicatedSeat->id = Str::uuid()->toString();
                    $replicatedSeat->saveWithoutEvents();
                }
            }
        }
    }
}
