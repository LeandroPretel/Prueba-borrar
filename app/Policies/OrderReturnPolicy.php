<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class OrderReturnPolicy extends SavitarPolicy
{
    protected static $model = 'order-returns';
}
