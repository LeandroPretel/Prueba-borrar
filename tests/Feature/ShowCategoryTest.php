<?php

namespace Tests\Feature;

use App\ShowCategory;
use Savitar\Auth\Database\seeders\SavitarUsersTableSeeder;
use Savitar\Models\Tests\SavitarTestCRUD;

class ShowCategoryTest extends SavitarTestCRUD
{
    protected $modelClass = ShowCategory::class;
    protected $baseRoute = 'show-categories';

    /**
     * Run the specified seeders for the tests.
     */
    protected function runSeedersOnce(): void
    {
        $this->seed(SavitarUsersTableSeeder::class);
    }
}
