<?php

namespace App\Observers;

use App\PaymentAttempt;
use Exception;

class PaymentAttemptObserver
{
    /**
     * Handle the PaymentAttempt "creating" event.
     *
     * @param PaymentAttempt $paymentAttempt
     * @return void
     * @throws Exception
     */
    public function creating(PaymentAttempt $paymentAttempt): void
    {
        // $paymentAttempt->date = Carbon::now();

    }

    /**
     * Handle the PaymentAttempt "created" event.
     *
     * @param PaymentAttempt $paymentAttempt
     * @return void
     */
    public function created(PaymentAttempt $paymentAttempt): void
    {
        // Successful attempt, add the amount paid
        if ($paymentAttempt->status === 'successful') {
            $this->updateOrder($paymentAttempt);
        }
    }

    /**
     * Handle the PaymentAttempt "updated" event.
     *
     * @param PaymentAttempt $paymentAttempt
     * @return void
     */
    public function updated(PaymentAttempt $paymentAttempt): void
    {
        // Successful attempt, add the amount paid
        if ($paymentAttempt->status === 'successful' && $paymentAttempt->getOriginal('status') === 'attempt') {
            $this->updateOrder($paymentAttempt);
        }
    }

    /**
     * Updates and add a successful payment to an order
     * @param PaymentAttempt $paymentAttempt
     */
    private function updateOrder(PaymentAttempt $paymentAttempt): void
    {
        $order = $paymentAttempt->order;
        $order->amountPaid += $paymentAttempt->amount;
        $order->amountPending -= $paymentAttempt->amount;
        if ($order->amountPending > 0) {
            $order->status = 'partially-paid';
        } else {
            $order->status = 'paid';
        }
        $order->updatedBy = $paymentAttempt->updatedBy;
        $order->save();
    }
}
