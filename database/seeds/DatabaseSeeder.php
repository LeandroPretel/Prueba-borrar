<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(AbilitiesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(ArtistsTableSeeder::class);
        $this->call(ShowCategoryTableSeeder::class);
        $this->call(ShowClassificationsTableSeeder::class);
        $this->call(ShowTemplatesTableSeeder::class);
        $this->call(ShowCategoryShowTemplatesTableSeeder::class);
        $this->call(ArtistShowTemplatesTableSeeder::class);
        $this->call(ShowsTableSeeder::class);
        $this->call(PlacesTableSeeder::class);
        $this->call(SpacesTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(SectorsTableSeeder::class);
        $this->call(SeatsTableSeeder::class);
        $this->call(SessionsTableSeeder::class);
        $this->call(FaresTableSeeder::class);
        //$this->call(AreaFaresTableSeeder::class);
        $this->call(PointsOfSaleTableSeeder::class);
        $this->call(PointOfSaleUserTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(DiscountsTableSeeder::class);
        $this->call(OrderReturnReasonsTableSeeder::class);
        $this->call(OrderReturnsTableSeeder::class);
        $this->call(AccessesTableSeeder::class);
        $this->call(TicketSeasonsTableSeeder::class);
        $this->call(TicketSeasonOrdersTableSeeder::class);
        $this->call(TicketVouchersTableSeeder::class);
        $this->call(TicketVoucherOrdersTableSeeder::class);
        $this->call(EnterprisesTableSeeder::class);
        $this->call(BrandAbilitiesTableSeeder::class);
    }
}
