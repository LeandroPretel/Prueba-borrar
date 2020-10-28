<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class TicketPolicy extends SavitarPolicy
{
    protected static $model = 'tickets';
}
