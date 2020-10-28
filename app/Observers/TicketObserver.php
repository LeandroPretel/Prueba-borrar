<?php

namespace App\Observers;

use App\Http\Controllers\AnfixController;
use App\Http\Controllers\UtilController;
use App\Jobs\AnfixEntry;
use App\SessionSeat;
use App\Ticket;
use Exception;

class TicketObserver
{
    /**
     * Handle the ticket "creating" event.
     *
     * @param Ticket $ticket
     * @return void
     * @throws Exception
     */
    public function creating(Ticket $ticket): void
    {
        if (!$ticket->locator) {
            $ticket->locator = UtilController::generateUniqueLocator(6);
            // Check unique locator
            $existingTicket = Ticket::whereLocator($ticket->locator)->first();
            while ($existingTicket) {
                $ticket->locator = UtilController::generateUniqueLocator(6);
                $existingTicket = Ticket::whereLocator($ticket->locator)->first();
            }
        }

        if ($ticket->baseAmount && !$ticket->baseAmountWithDiscount) {
            $ticket->baseAmountWithDiscount = $ticket->baseAmount;
        }
        if ($ticket->distributionAmount && !$ticket->distributionAmountWithDiscount) {
            $ticket->distributionAmountWithDiscount = $ticket->distributionAmount;
        }
        if ($ticket->amount && !$ticket->amountWithDiscount) {
            $ticket->amountWithDiscount = $ticket->amount;
        }
    }

    /**
     * Handle the ticket "created" event.
     *
     * @param Ticket $ticket
     * @return void
     * @throws Exception
     */
    public function created(Ticket $ticket): void
    {
        // Agoto la sesión si ya no quedan entradas
        $freeSessionSeats = SessionSeat::whereSessionId($ticket->sessionId)
            ->where('status', 'enabled')->count();
        if ($freeSessionSeats === 0) {
            $ticket->session->status = 'Agotada';
            $ticket->session->save();
        }

        // Send to Anfix
        AnfixEntry::dispatch($ticket)->onQueue('anfix');
    }

    /**
     * Handle the ticket "created" event.
     *
     * @param Ticket $ticket
     * @return void
     * @throws Exception
     */
    public function deleted(Ticket $ticket): void
    {
        // Was an invitation
        if (!$ticket->orderId) {
            $sessionSeat = SessionSeat::find($ticket->sessionSeatId);
            if ($sessionSeat) {
                $sessionSeat->status = 'enabled';
                $sessionSeat->save();
            }
        }
    }
}
