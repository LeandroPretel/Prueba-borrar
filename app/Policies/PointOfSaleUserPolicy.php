<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class PointOfSaleUserPolicy extends SavitarPolicy
{
    protected static $model = 'point-of-sale-users';
}
