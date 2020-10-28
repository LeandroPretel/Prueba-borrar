<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class TicketSeasonPolicy extends SavitarPolicy
{
    protected static $model = 'ticket-seasons';
}
