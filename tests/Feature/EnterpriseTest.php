<?php

namespace Tests\Feature;

use App\Enterprise;
use Savitar\Auth\Database\seeders\SavitarUsersTableSeeder;
use Savitar\Auth\SavitarZone;
use Savitar\Models\Tests\SavitarTestCRUD;

class EnterpriseTest extends SavitarTestCRUD
{
    protected $modelClass = Enterprise::class;
    protected $baseRoute = 'enterprises';

    /**
     * Run the specified seeders for the tests.
     */
    protected function runSeedersOnce(): void
    {
        $this->seed(SavitarUsersTableSeeder::class);
    }

    /**
     * @param $arrayOfAttributes
     */
    protected function fakeRelationShips(&$arrayOfAttributes): void
    {
        $arrayOfAttributes['countryId'] = SavitarZone::whereSlug('espana')->first()->id;
        $arrayOfAttributes['provinceId'] = SavitarZone::whereSlug('granada')->first()->id;
    }
}
