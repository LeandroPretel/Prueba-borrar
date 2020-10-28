<?php

namespace Tests\Feature;

use App\Artist;
use Savitar\Auth\Database\seeders\SavitarUsersTableSeeder;
use Savitar\Models\Tests\SavitarTestCRUD;

class ArtistTest extends SavitarTestCRUD
{
    protected $modelClass = Artist::class;
    protected $baseRoute = 'artists';

    /**
     * Run the specified seeders for the tests.
     */
    protected function runSeedersOnce(): void
    {
        $this->seed(SavitarUsersTableSeeder::class);
    }
}
