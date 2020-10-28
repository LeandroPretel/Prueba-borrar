<?php

use App\Http\Controllers\SessionStatusController;
use Illuminate\Database\Seeder;
use old\AddIdsTableSeeder;
use old\ClientsSeasonTableSeeder;
use old\DoorsTableSeeder;
use old\FixSeatsTableSeeder;
use old\SeasonOrderSessionsTableSeeder;
use old\SessionAreasTableSeeder;
use old\SessionDoorsTableSeeder;
use old\SessionSeatsTableSeeder;
use old\SessionSectorsTableSeeder;
use old\ShowCategoriesTableSeeder;
use old\TicketSeasonGroupsTableSeeder;

class DatabaseSeederOld extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $startTime = microtime(true);
        // Essentials
        DB::disableQueryLog();
        DB::connection()->disableQueryLog();

        DB::connection("pgsql")->unsetEventDispatcher();
        DB::connection("oldRedentradas")->unsetEventDispatcher();
        $this->call(AbilitiesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(AddIdsTableSeeder::class);
        // Base
        $this->call(\old\EnterprisesTableSeeder::class);
        $this->call(\old\PartnersTableSeeder::class);
        $this->call(\old\AccountsTableSeeder::class);
        $this->call(\old\PointsOfSaleTableSeeder::class);
        // Recintos
        $this->call(\old\PlacesTableSeeder::class);
        $this->call(\old\SpacesTableSeeder::class);
        $this->call(DoorsTableSeeder::class);
        $this->call(\old\AreasTableSeeder::class);
        $this->call(\old\SectorsTableSeeder::class);
        $this->call(\old\SeatsTableSeeder::class);
        $this->call(FixSeatsTableSeeder::class);
        // Espectáculos
        $this->call(TicketSeasonGroupsTableSeeder::class);
        $this->call(\old\ArtistsTableSeeder::class);
        $this->call(ShowCategoriesTableSeeder::class);
        $this->call(\old\ShowTemplatesTableSeeder::class);
        // Sesiones y abonos
        $this->call(\old\ShowsTableSeeder::class);
        $this->call(\old\SessionsTableSeeder::class);
        $this->call(SessionAreasTableSeeder::class);
        $this->call(SessionSectorsTableSeeder::class);
        $this->call(SessionDoorsTableSeeder::class);
        $this->call(\old\TicketSeasonsTableSeeder::class);
        $this->call(\old\FaresTableSeeder::class);
        // Clientes & abonados
        $this->call(ClientsSeasonTableSeeder::class);
        $this->call(\old\ClientsTableSeeder::class);
        // Entradas*/
        $this->call(SessionSeatsTableSeeder::class);
        $this->call(SeasonOrderSessionsTableSeeder::class);

        // Para el estado de las sesiones
        $sessionStatusController = new SessionStatusController();
        $sessionStatusController->__invoke();
        //$this->call(\old\UserCypherSeeder::class);
        $runTime = round(microtime(true) - $startTime, 2);
        echo "\e[0;32mTOTAL MIGRATION TIME:\e[0m {$runTime} seconds\n";
    }

}
