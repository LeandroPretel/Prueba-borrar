<?php

namespace App\Observers;

use App\Discount;
use Illuminate\Support\Str;

class DiscountObserver
{
    /**
     * Handle the discount "creating" event.
     *
     * @param Discount $discount
     * @return void
     */
    public function creating(Discount $discount): void
    {
        // If the discount has no code, generate one randomly
        if (!$discount->code) {
            $discount->code = Str::random(8);
        }
    }

}
