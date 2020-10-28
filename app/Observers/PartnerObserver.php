<?php

namespace App\Observers;

use App\Partner;

class PartnerObserver
{
    /**
     * Handle the partner "created" event.
     *
     * @param Partner $partner
     * @return void
     */
    public function created(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "updated" event.
     *
     * @param Partner $partner
     * @return void
     */
    public function updated(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "deleted" event.
     *
     * @param Partner $partner
     * @return void
     */
    public function deleted(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "restored" event.
     *
     * @param Partner $partner
     * @return void
     */
    public function restored(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "force deleted" event.
     *
     * @param Partner $partner
     * @return void
     */
    public function forceDeleted(Partner $partner)
    {
        //
    }
}
