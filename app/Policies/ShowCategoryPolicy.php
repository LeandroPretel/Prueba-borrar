<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class ShowCategoryPolicy extends SavitarPolicy
{
    protected static $model = 'show-categories';
}
