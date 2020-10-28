<?php

namespace App\Observers;

use App\TicketVoucher;

class TicketVoucherObserver
{
    /**
     * Handle the ticket voucher "created" event.
     *
     * @param TicketVoucher $ticketVoucher
     * @return void
     */
    public function created(TicketVoucher $ticketVoucher)
    {
        //
    }

    /**
     * Handle the ticket voucher "updated" event.
     *
     * @param TicketVoucher $ticketVoucher
     * @return void
     */
    public function updated(TicketVoucher $ticketVoucher)
    {
        //
    }

    /**
     * Handle the ticket voucher "deleted" event.
     *
     * @param TicketVoucher $ticketVoucher
     * @return void
     */
    public function deleted(TicketVoucher $ticketVoucher)
    {
        //
    }

    /**
     * Handle the ticket voucher "restored" event.
     *
     * @param TicketVoucher $ticketVoucher
     * @return void
     */
    public function restored(TicketVoucher $ticketVoucher)
    {
        //
    }

    /**
     * Handle the ticket voucher "force deleted" event.
     *
     * @param TicketVoucher $ticketVoucher
     * @return void
     */
    public function forceDeleted(TicketVoucher $ticketVoucher)
    {
        //
    }
}
