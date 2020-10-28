<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class AccessPolicy extends SavitarPolicy
{
    protected static $model = 'accesses';
}
