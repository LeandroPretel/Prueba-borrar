<?php

namespace App\Observers;

use App\TicketSeason;

class TicketSeasonObserver
{
    /**
     * Handle the ticket season "created" event.
     *
     * @param TicketSeason $ticketSeason
     * @return void
     */
    public function created(TicketSeason $ticketSeason)
    {
        //
    }

    /**
     * Handle the ticket season "updated" event.
     *
     * @param TicketSeason $ticketSeason
     * @return void
     */
    public function updated(TicketSeason $ticketSeason)
    {
        //
    }

    /**
     * Handle the ticket season "deleted" event.
     *
     * @param TicketSeason $ticketSeason
     * @return void
     */
    public function deleted(TicketSeason $ticketSeason)
    {
        //
    }

    /**
     * Handle the ticket season "restored" event.
     *
     * @param TicketSeason $ticketSeason
     * @return void
     */
    public function restored(TicketSeason $ticketSeason)
    {
        //
    }

    /**
     * Handle the ticket season "force deleted" event.
     *
     * @param TicketSeason $ticketSeason
     * @return void
     */
    public function forceDeleted(TicketSeason $ticketSeason)
    {
        //
    }
}
