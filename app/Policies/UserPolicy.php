<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class UserPolicy extends SavitarPolicy
{
    protected static $model = 'users';
}
