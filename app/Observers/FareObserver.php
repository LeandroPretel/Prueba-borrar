<?php

namespace App\Observers;

use App\Fare;

class FareObserver
{
    /**
     * Handle the fare "created" event.
     *
     * @param Fare $fare
     * @return void
     */
    public function created(Fare $fare): void
    {
//        Uncomment for set prices to test
//        foreach ($fare->session->space->areas as $area) {
//            $amount = 0;
//            if ($fare->name == 'Tarifa general') {
//                $amount = rand(30, 50);
//            } else if($fare->name == 'Estudiantes/pensionistas') {
//                $amount = rand(10, 25);
//            }
//            $fare->areas()->attach([$area->id => ['earlyTotalPrice' => $amount]]);
//        }
        $sessionAreas = $fare->session->space->sessionAreas()->where('sessionId', $fare->sessionId)->get();
        $fare->sessionAreas()->attach($sessionAreas);
    }

    /**
     * Handle the fare "updated" event.
     *
     * @param Fare $fare
     * @return void
     */
    public function updated(Fare $fare)
    {
        //
    }

    /**
     * Handle the fare "deleted" event.
     *
     * @param Fare $fare
     * @return void
     */
    public function deleted(Fare $fare)
    {
        //
    }

    /**
     * Handle the fare "restored" event.
     *
     * @param Fare $fare
     * @return void
     */
    public function restored(Fare $fare)
    {
        //
    }

    /**
     * Handle the fare "force deleted" event.
     *
     * @param Fare $fare
     * @return void
     */
    public function forceDeleted(Fare $fare)
    {
        //
    }
}
