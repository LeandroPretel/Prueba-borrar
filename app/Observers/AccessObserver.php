<?php

namespace App\Observers;

use App\Access;

class AccessObserver
{
    /**
     * Handle the access "created" event.
     *
     * @param Access $access
     * @return void
     */
    public function created(Access $access)
    {
        //
    }

    /**
     * Handle the access "updated" event.
     *
     * @param Access $access
     * @return void
     */
    public function updated(Access $access)
    {
        //
    }

    /**
     * Handle the access "deleted" event.
     *
     * @param Access $access
     * @return void
     */
    public function deleted(Access $access)
    {
        //
    }

    /**
     * Handle the access "restored" event.
     *
     * @param Access $access
     * @return void
     */
    public function restored(Access $access)
    {
        //
    }

    /**
     * Handle the access "force deleted" event.
     *
     * @param Access $access
     * @return void
     */
    public function forceDeleted(Access $access)
    {
        //
    }
}
