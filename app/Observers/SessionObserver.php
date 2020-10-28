<?php

namespace App\Observers;

use App\Fare;
use App\Place;
use App\Session;
use App\SessionArea;
use App\SessionDoor;
use App\SessionSeat;
use App\SessionSector;
use App\Show;
use App\Space;
use Carbon\Carbon;

class SessionObserver
{
    /**
     * Handle the session "created" event.
     *
     *
     * @param Session $session
     * @return void
     */
    public function creating(Session $session): void
    {
        if (!$session->advanceSaleStartDate) {
            $session->advanceSaleStartDate = Carbon::now();
        }
        if (!$session->advanceSaleFinishDate) {
//            $session->advanceSaleFinishDate = Carbon::parse($session->date)->subDay();
            $session->advanceSaleFinishDate = Carbon::parse($session->date);
        }
    }

    /**
     * Handle the session "created" event.
     *
     * @param Session $session
     * @return void
     */
    public function created(Session $session): void
    {
        // Create the sessionDoors.
        $associatedDoors = [];
        /** @var Place $place */
        $place = $session->place()->with('doors')->first();
        foreach ($place->doors as $door) {
            $sessionDoor = new SessionDoor();
            $sessionDoor->session()->associate($session);
            $sessionDoor->place()->associate($place);
            $sessionDoor->name = $door->name;
            $sessionDoor->webName = $door->webName;
            $sessionDoor->ticketName = $door->ticketName;
            $sessionDoor->order = $door->order;
            $sessionDoor->observations = $door->observations;
            $sessionDoor->save();
            $associatedDoors[$door->id] = $sessionDoor->id;
        }
        // Create the sessionAreas, sessionSectors and sessionSeats.
        /** @var Space $space */
        $space = $session->space()->with(['areas.sectors.seats.doors', 'areas.sectors.doors'])->first();
        if ($space) {
            foreach ($space->areas as $area) {
                $sessionArea = new SessionArea();
                $sessionArea->session()->associate($session);
                $sessionArea->space()->associate($space);
                $sessionArea->name = $area->name;
                $sessionArea->webName = $area->webName;
                $sessionArea->ticketName = $area->ticketName;
                $sessionArea->color = $area->color;
                $sessionArea->totalSeats = $area->totalSeats;
                $sessionArea->order = $area->order;
                $sessionArea->observations = $area->observations;
                $sessionArea->save();
                foreach ($area->sectors as $sector) {
                    $sessionSector = new SessionSector();
                    $sessionSector->session()->associate($session);
                    $sessionSector->sessionArea()->associate($sessionArea);
                    $sessionSector->name = $sector->name;
                    $sessionSector->webName = $sector->webName;
                    $sessionSector->ticketName = $sector->ticketName;
                    $sessionSector->isNumbered = $sector->isNumbered;
                    $sessionSector->totalSeats = $sector->totalSeats;
                    $sessionSector->rows = $sector->rows;
                    $sessionSector->columns = $sector->columns;
                    $sessionSector->disabledAccess = $sector->disabledAccess;
                    $sessionSector->reducedVisibility = $sector->reducedVisibility;
                    $sessionSector->stageLocation = $sector->stageLocation;
                    $sessionSector->points = $sector->points;
                    $sessionSector->centers = $sector->centers;
                    $sessionSector->order = $sector->order;
                    $sessionSector->observations = $sector->observations;
                    $sessionSector->save();

                    $arrayToSync = [];
                    foreach ($sector->doors as $door) {
                        $arrayToSync[] = $associatedDoors[$door->id];
                    }
                    $sessionSector->sessionDoors()->sync($arrayToSync);

                    foreach ($sector->seats as $seat) {
                        $sessionSeat = new SessionSeat();
                        $sessionSeat->session()->associate($session);
                        $sessionSeat->sessionSector()->associate($sessionSector);
                        $sessionSeat->row = $seat->row;
                        $sessionSeat->column = $seat->column;
                        $sessionSeat->rowName = $seat->rowName;
                        $sessionSeat->number = $seat->number;
                        $sessionSeat->isForDisabled = $seat->isForDisabled;
                        $sessionSeat->reducedVisibility = $seat->reducedVisibility;
                        $sessionSeat->status = $seat->status;
                        $sessionSeat->lockReason = $seat->lockReason;
                        $sessionSeat->save();

                        $arrayToSync = [];
                        foreach ($seat->doors as $door) {
                            $arrayToSync[] = $associatedDoors[$door->id];
                        }

                        $sessionSeat->sessionDoors()->sync($arrayToSync);
                    }
                }
            }
        }

        // Create slug for the show
        if (!$session->show->slug) {
            $possibleSlug = $session->showTemplate->name;
            $existingShow = Show::whereSlug($possibleSlug)->first();
            if ($existingShow) {
                $session->show->slug = $possibleSlug . '-' . $session->date->year;
            } else {
                $session->show->slug = $possibleSlug;
            }
            $session->show->save();
        }

        // Create the default fare
        $fare = new Fare();
        $fare->session()->associate($session);
        $fare->name = 'Tarifa general';
        $fare->webName = 'Tarifa general';
        $fare->ticketName = 'Tarifa general';
        $fare->description = 'Tarifa general';
        $fare->save();

        $session->defaultFare()->associate($fare);
        $session->saveWithoutEvents();
        /*
        $fare = new Fare();
        $fare->session()->associate($session);
        $fare->name = 'Estudiantes/pensionistas';
        $fare->description = 'Entrada válida para estudiantes, jubilados o pensionistas, presentando el carnet correspondiente en el acceso al concierto.';
        $fare->save();
        */
    }

    /**
     * Handle the session "updated" event.
     *
     * @param Session $session
     * @return void
     */
    public function updated(Session $session)
    {
        //
    }

    /**
     * Handle the session "deleted" event.
     *
     * @param Session $session
     * @return void
     */
    public function deleted(Session $session)
    {
        //
    }

    /**
     * Handle the session "restored" event.
     *
     * @param Session $session
     * @return void
     */
    public function restored(Session $session)
    {
        //
    }

    /**
     * Handle the session "force deleted" event.
     *
     * @param Session $session
     * @return void
     */
    public function forceDeleted(Session $session)
    {
        //
    }
}
