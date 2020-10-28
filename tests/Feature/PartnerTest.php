<?php

namespace Tests\Feature;

use App\Partner;
use Savitar\Auth\Database\seeders\SavitarUsersTableSeeder;
use Savitar\Models\Tests\SavitarTestCRUD;

class PartnerTest extends SavitarTestCRUD
{
    protected $modelClass = Partner::class;
    protected $baseRoute = 'partners';

    /**
     * Run the specified seeders for the tests.
     */
    protected function runSeedersOnce(): void
    {
        $this->seed(SavitarUsersTableSeeder::class);
    }
}
