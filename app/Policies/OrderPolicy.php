<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class OrderPolicy extends SavitarPolicy
{
    protected static $model = 'orders';
}
