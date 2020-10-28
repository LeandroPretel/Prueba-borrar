<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class SessionPolicy extends SavitarPolicy
{
    protected static $model = 'sessions';
}
