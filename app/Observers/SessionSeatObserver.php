<?php

namespace App\Observers;

use App\SessionSeat;

class SessionSeatObserver
{
    /**
     * Handle the session seat "created" event.
     *
     * @param SessionSeat $sessionSeat
     * @return void
     */
    public function created(SessionSeat $sessionSeat)
    {
        //
    }

    /**
     * Handle the session seat "updated" event.
     *
     * @param SessionSeat $sessionSeat
     * @return void
     */
    public function updated(SessionSeat $sessionSeat)
    {
        //
    }

    /**
     * Handle the session seat "deleted" event.
     *
     * @param SessionSeat $sessionSeat
     * @return void
     */
    public function deleted(SessionSeat $sessionSeat)
    {
        //
    }

    /**
     * Handle the session seat "restored" event.
     *
     * @param SessionSeat $sessionSeat
     * @return void
     */
    public function restored(SessionSeat $sessionSeat)
    {
        //
    }

    /**
     * Handle the session seat "force deleted" event.
     *
     * @param SessionSeat $sessionSeat
     * @return void
     */
    public function forceDeleted(SessionSeat $sessionSeat)
    {
        //
    }
}
