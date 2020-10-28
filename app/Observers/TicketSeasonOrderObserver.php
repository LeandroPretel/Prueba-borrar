<?php

namespace App\Observers;

use App\TicketSeasonOrder;

class TicketSeasonOrderObserver
{
    /**
     * Handle the ticket season order "created" event.
     *
     * @param TicketSeasonOrder $ticketSeasonOrder
     * @return void
     */
    public function created(TicketSeasonOrder $ticketSeasonOrder)
    {
        //
    }

    /**
     * Handle the ticket season order "updated" event.
     *
     * @param TicketSeasonOrder $ticketSeasonOrder
     * @return void
     */
    public function updated(TicketSeasonOrder $ticketSeasonOrder)
    {
        //
    }

    /**
     * Handle the ticket season order "deleted" event.
     *
     * @param TicketSeasonOrder $ticketSeasonOrder
     * @return void
     */
    public function deleted(TicketSeasonOrder $ticketSeasonOrder)
    {
        //
    }

    /**
     * Handle the ticket season order "restored" event.
     *
     * @param TicketSeasonOrder $ticketSeasonOrder
     * @return void
     */
    public function restored(TicketSeasonOrder $ticketSeasonOrder)
    {
        //
    }

    /**
     * Handle the ticket season order "force deleted" event.
     *
     * @param TicketSeasonOrder $ticketSeasonOrder
     * @return void
     */
    public function forceDeleted(TicketSeasonOrder $ticketSeasonOrder)
    {
        //
    }
}
