<?php

use App\Enterprise;
use App\PointOfSale;
use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarZone;

class EnterprisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $enterprise = new Enterprise();
        $enterprise->name = 'Empresa de prueba';
        $enterprise->socialReason = 'Empresa de prueba';
        $enterprise->address = 'C/ Ancha de la Virgen, 27';
        $enterprise->city = 'Granada';
        $enterprise->zipCode = '18009';
        $enterprise->nif = 'B18989871';
        $enterprise->province()->associate(SavitarZone::whereName('Granada')->first());
        $enterprise->chargeIban = 'NL74INGB5350244469';
        $enterprise->paymentIban = 'NL74INGB5350244469';
        $enterprise->save();

        $enterprise->pointsOfSale()->attach(PointOfSale::all());
    }
}
