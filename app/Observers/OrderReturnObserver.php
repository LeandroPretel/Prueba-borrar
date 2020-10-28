<?php

namespace App\Observers;

use App\Jobs\AnfixReturn;
use App\Order;
use App\OrderReturn;
use App\PointOfSale;
use App\SessionSeat;
use App\Ticket;
use Carbon\Carbon;

class OrderReturnObserver
{
    /**
     * Handle the seat "created" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function saving(OrderReturn $orderReturn): void
    {
        if ($orderReturn->status !== 'attempt') {
            $orderReturn->date = Carbon::now();
        }
        if (!$orderReturn->pointOfSaleId) {
            $pointOfSale = PointOfSale::where('slug', 'redentradas')->first();
            if ($pointOfSale) {
                $orderReturn->pointOfSale()->associate($pointOfSale);
            }
        }
        if ($orderReturn->mode !== 'tpv') {
            $orderReturn->status = 'successful';
        }
    }

    /**
     * Handle the seat "created" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function saved(OrderReturn $orderReturn): void
    {
        // Make a return of a ticket/s
        if (request()->filled('ticketIds')) {
            Ticket::whereIn('id', request()->input('ticketIds'))
                ->update(['orderReturnId' => $orderReturn->id]);
        }
        // Make a return of an order
        if (request()->filled('orderId')) {
            $order = Order::find(request()->input('orderId'));
            $order->tickets()->update(['orderReturnId' => $orderReturn->id]);
        }
        // Free sessionSeats if the orderReturn is successful
        if ($orderReturn->status === 'successful' && $orderReturn->getOriginal('status') !== 'successful') {
            $sessionSeatIds = $orderReturn->tickets()->select('sessionSeatId')->get();
            SessionSeat::whereIn('id', $sessionSeatIds)->update(['status' => 'enabled', 'updatedAt' => Carbon::now()]);

            // Send to Anfix
            AnfixReturn::dispatch($orderReturn)->onQueue('anfix');
        }
    }

    /**
     * Handle the seat "created" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function creating(OrderReturn $orderReturn): void
    {
        $orderReturn->attemptDate = Carbon::now();
    }

    /**
     * Handle the seat "created" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function created(OrderReturn $orderReturn): void
    {

    }

    /**
     * Handle the seat "updated" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function updated(OrderReturn $orderReturn)
    {
        //
    }

    /**
     * Handle the seat "deleted" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function deleted(OrderReturn $orderReturn)
    {
        //
    }

    /**
     * Handle the seat "restored" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function restored(OrderReturn $orderReturn)
    {
        //
    }

    /**
     * Handle the seat "force deleted" event.
     *
     * @param OrderReturn $orderReturn
     * @return void
     */
    public function forceDeleted(OrderReturn $orderReturn)
    {
        //
    }
}
