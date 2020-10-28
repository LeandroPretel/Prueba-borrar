<?php

namespace App\Observers;

use App\Enterprise;

class EnterpriseObserver
{
    /**
     * Handle the enterprise "created" event.
     *
     * @param Enterprise $enterprise
     * @return void
     */
    public function created(Enterprise $enterprise)
    {
        //
    }

    /**
     * Handle the enterprise "updated" event.
     *
     * @param Enterprise $enterprise
     * @return void
     */
    public function updated(Enterprise $enterprise)
    {
        //
    }

    /**
     * Handle the enterprise "deleted" event.
     *
     * @param Enterprise $enterprise
     * @return void
     */
    public function deleted(Enterprise $enterprise)
    {
        //
    }

    /**
     * Handle the enterprise "restored" event.
     *
     * @param Enterprise $enterprise
     * @return void
     */
    public function restored(Enterprise $enterprise)
    {
        //
    }

    /**
     * Handle the enterprise "force deleted" event.
     *
     * @param Enterprise $enterprise
     * @return void
     */
    public function forceDeleted(Enterprise $enterprise)
    {
        //
    }
}
