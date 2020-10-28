<?php

namespace App\Observers;

use App\Area;
use App\Seat;
use App\Sector;

class SectorObserver
{
    /**
     * Handle the sector "saving" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function saving(Sector $sector): void
    {
        // Save the center and the points
        if (request()->has('points')) {
            $sector->points = json_encode(request()->input('points'));
        }
        if (request()->has('centers')) {
            $sector->centers = json_encode(request()->input('centers'));
        }

        if ($sector->isNumbered && request()->has('seats')) {
            $seatsCount = 0;
            foreach (request()->input('seats') as $row) {
                $seatsCount += count(collect($row)->where('status', '<>', 'deleted'));
            }
            $sector->totalSeats = $seatsCount;
        }
    }

    /**
     * Handle the sector "saved" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function saved(Sector $sector): void
    {
        if (request()->has('doorsIds')) {
            $arrayToSync = [];
            foreach (request()->input('doorsIds') as $doorId) {
                $arrayToSync[] = $doorId;
            }
            $sector->doors()->sync($arrayToSync);
        }

        // If the sector is numbered, save the seats
        if (request()->input('isNumbered') && request()->has('seats')) {
            foreach (request()->input('seats') as $row) {
                foreach ($row as $requestSeat) {
                    if (isset($requestSeat['id'])) {
                        $seat = Seat::find($requestSeat['id']);
                    } else {
                        $seat = new Seat();
                        $seat->sector()->associate($sector);
                    }
                    if (isset($requestSeat['row'])) {
                        $seat->row = $requestSeat['row'];
                    }
                    if (isset($requestSeat['column'])) {
                        $seat->column = $requestSeat['column'];
                    }
                    isset($requestSeat['rowName'])
                        ? $seat->rowName = $requestSeat['rowName']
                        : $seat->rowName = $seat->row;
                    if (isset($requestSeat['number'])) {
                        $seat->number = $requestSeat['number'];
                    } else {
                        $seat->number = null;
                    }
                    if (isset($requestSeat['isForDisabled'])) {
                        $seat->isForDisabled = $requestSeat['isForDisabled'];
                    }
                    if (isset($requestSeat['reducedVisibility'])) {
                        $seat->reducedVisibility = $requestSeat['reducedVisibility'];
                    }
                    if (isset($requestSeat['status'])) {
                        $seat->status = $requestSeat['status'];
                    }
                    if (isset($requestSeat['lockReason'])) {
                        $seat->lockReason = $requestSeat['lockReason'];
                    }
                    if (isset($requestSeat['observations'])) {
                        $seat->observations = $requestSeat['observations'];
                    }

                    $seat->save();

                    $arrayToSync = [];
                    if (isset($requestSeat['doors'])) {
                        foreach ($requestSeat['doors'] as $door) {
                            if ($door) {
                                $arrayToSync[] = $door['id'];
                            }
                        }
                        $seat->doors()->sync($arrayToSync);
                    }
                }
            }
        }

        // Recalculate the totalSeats of the area.
        /** @var Area $area */
        $area = $sector->area()->with('sectors')->first();
        $totalSeats = 0;
        foreach ($area->sectors as $areaSector) {
            $totalSeats += $areaSector->totalSeats;
        }
        $area->totalSeats = $totalSeats;
        $area->saveWithoutEvents();
    }

    /**
     * Handle the sector "created" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function created(Sector $sector): void
    {
        $this->createSeatsForNotNumbered($sector);
    }

    /**
     * Creates "fake" seats will row, column and rowName = null for not numbered sectors.
     *
     * @param Sector $sector
     */
    private function createSeatsForNotNumbered(Sector $sector): void
    {
        if (!$sector->isNumbered) {
            $numberOfSeats = $sector->totalSeats;
            while ($numberOfSeats > 0) {
                $seat = new Seat();
                $seat->sector()->associate($sector);
                $seat->save();
                $numberOfSeats--;
            }
        }
    }

    /**
     * Handle the sector "updating" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function updating(Sector $sector): void
    {
        if ($sector->getOriginal('isNumbered') !== $sector->isNumbered) {
            // Save the seats of the sector, if the sector is not numbered, delete the row, rowName, column, number.
            if (!$sector->isNumbered) {
                // Sector changed to NOT Numbered
                // Delete all seats and create the "fake" seats with null values
                $sector->seats()->delete();
                $this->createSeatsForNotNumbered($sector);
//                $seatIds = $sector->seats()->select('id')->get();
//                Seat::whereIn('id', $seatIds)->update([
//                    'row' => null,
//                    'rowName' => null,
//                    'column' => null,
//                    'number' => null,
//                ]);
            } else {
                // Sector changed to Numbered
                // Delete "fake" seats with null values
                $sector->seats()->where('row', '=', null)->orWhere('column', '=', null)->delete();
            }
        }
    }

    /**
     * Handle the sector "updated" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function updated(Sector $sector): void
    {
        // The sector is not numbered, and the number of seats changed
        if (!$sector->isNumbered && $sector->wasChanged('totalSeats')) {
            $numberOfSeats = $sector->totalSeats - $sector->getOriginal('totalSeats');
            if ($numberOfSeats > 0) {
                while ($numberOfSeats > 0) {
                    $seat = new Seat();
                    $seat->sector()->associate($sector);
                    $seat->save();
                    $numberOfSeats--;
                }
            } // Delete seats
            else {
                $sector->seats()->limit($numberOfSeats * -1)->delete();
            }
        }
    }

    /**
     * Handle the sector "deleted" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function deleted(Sector $sector)
    {
        //
    }

    /**
     * Handle the sector "restored" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function restored(Sector $sector)
    {
        //
    }

    /**
     * Handle the sector "force deleted" event.
     *
     * @param Sector $sector
     * @return void
     */
    public function forceDeleted(Sector $sector)
    {
        //
    }
}
