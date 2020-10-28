<?php

namespace App\Observers;

use App\Door;

class DoorObserver
{
    /**
     * Handle the door "creating" event.
     *
     * @param Door $door
     * @return void
     */
    public function creating(Door $door): void
    {
    }
}
