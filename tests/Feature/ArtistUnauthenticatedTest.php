<?php

namespace Tests\Feature;

use App\Artist;
use ArtistsTableSeeder;
use Savitar\Auth\Database\seeders\SavitarUsersTableSeeder;
use Savitar\Models\Tests\SavitarTestUnauthorizedCRUD;

class ArtistUnauthenticatedTest extends SavitarTestUnauthorizedCRUD
{
    protected $modelClass = Artist::class;
    protected $baseRoute = 'artists';

    /**
     * Run the specified seeders for the tests.
     */
    protected function runSeedersOnce(): void
    {
        $this->seed(SavitarUsersTableSeeder::class);
        $this->seed(ArtistsTableSeeder::class);
    }
}
