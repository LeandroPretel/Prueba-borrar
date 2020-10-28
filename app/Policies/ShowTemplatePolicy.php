<?php

namespace App\Policies;

use Savitar\Models\SavitarPolicy;

class ShowTemplatePolicy extends SavitarPolicy
{
    protected static $model = 'show-templates';
}
