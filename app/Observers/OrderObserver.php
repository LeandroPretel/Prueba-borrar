<?php

namespace App\Observers;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UtilController;
use App\Order;
use Exception;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     *
     * @param Order $order
     * @return void
     * @throws Exception
     */
    public function creating(Order $order): void
    {
        if (!$order->locator) {
            $order->locator = UtilController::generateUniqueLocator(6);
        }
        if (!$order->amountPending) {
            $order->amountPending = $order->amount;
        }

        if ($order->status === 'successful') {
            $orderController = new OrderController();
            // $orderController->generateNumber($order);
        }
    }

    /**
     * Handle the order "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the order "updating" event.
     *
     * @param Order $order
     * @return void
     * @throws Exception
     */
    public function updating(Order $order): void
    {
        if (!$order->number && $order->status === 'successful' && $order->getOriginal()['status'] !== 'successful') {
            // Asignación del número de la recarga
            $orderController = new OrderController();
            // $orderController->generateNumber($order);
        }
    }

    /**
     * Handle the order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param Order $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
