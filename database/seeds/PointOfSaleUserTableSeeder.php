<?php

use App\PointOfSale;
use App\PointOfSaleUser;
use App\User;
use Illuminate\Database\Seeder;

class PointOfSaleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::whereEmail('punto-de-venta@redentradas.es')->first();
        $pointOfSale = PointOfSale::whereName('Redentradas')->first();

        $pointOfSaleUser = new PointOfSaleUser();
        $pointOfSaleUser->user()->associate($user);
        $pointOfSaleUser->pointOfSale()->associate($pointOfSale);
        $pointOfSaleUser->save();
    }
}
