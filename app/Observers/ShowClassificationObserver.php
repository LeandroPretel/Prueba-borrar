<?php

namespace App\Observers;

use App\ShowClassification;

class ShowClassificationObserver
{
    /**
     * Handle the show classification "created" event.
     *
     * @param ShowClassification $showClassification
     * @return void
     */
    public function created(ShowClassification $showClassification)
    {
        //
    }

    /**
     * Handle the show classification "updated" event.
     *
     * @param ShowClassification $showClassification
     * @return void
     */
    public function updated(ShowClassification $showClassification)
    {
        //
    }

    /**
     * Handle the show classification "deleted" event.
     *
     * @param ShowClassification $showClassification
     * @return void
     */
    public function deleted(ShowClassification $showClassification)
    {
        //
    }

    /**
     * Handle the show classification "restored" event.
     *
     * @param ShowClassification $showClassification
     * @return void
     */
    public function restored(ShowClassification $showClassification)
    {
        //
    }

    /**
     * Handle the show classification "force deleted" event.
     *
     * @param ShowClassification $showClassification
     * @return void
     */
    public function forceDeleted(ShowClassification $showClassification)
    {
        //
    }
}
