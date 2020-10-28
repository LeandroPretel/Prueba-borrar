<?php

use App\PointOfSale;
use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarZone;

class PointsOfSaleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // $account = Account::where('email', 'promotor@redentradas.es')->first();
        $granada = SavitarZone::whereName('Granada')->first();
        $almeria = SavitarZone::whereName('Almería')->first();

        $pointOfSale = new PointOfSale();
        $pointOfSale->name = 'Redentradas';
        $pointOfSale->webName = 'Redentradas';
        $pointOfSale->ticketName = 'Redentradas';
        $pointOfSale->province()->associate($granada);
        $pointOfSale->isWeb = true;
        $pointOfSale->openingHours = 'Abierto todos los días 24 horas';
        $pointOfSale->ticketSalesEnabled = true;
        $pointOfSale->ticketPickUpEnabled = true;
        $pointOfSale->serviceServedEnabled = true;
        $pointOfSale->creditCardEnabled = true;
        $pointOfSale->cashEnabled = true;
        $pointOfSale->save();

        $pointOfSale = new PointOfSale();
        $pointOfSale->name = 'Teléfono Redentradas';
        $pointOfSale->webName = 'Teléfono Redentradas';
        $pointOfSale->ticketName = 'Teléfono Redentradas';
        $pointOfSale->province()->associate($granada);
        $pointOfSale->isByPhone = true;
        $pointOfSale->openingHours = 'Abierto todos los días 24 horas';
        $pointOfSale->ticketSalesEnabled = true;
        $pointOfSale->ticketPickUpEnabled = true;
        $pointOfSale->serviceServedEnabled = true;
        $pointOfSale->creditCardEnabled = true;
        $pointOfSale->cashEnabled = true;
        $pointOfSale->save();

        $pointOfSale = new PointOfSale();
        $pointOfSale->name = 'Exótico cultural tours';
        $pointOfSale->webName = 'Exótico cultural tours';
        $pointOfSale->ticketName = 'Exótico cultural tours';
        $pointOfSale->province()->associate($granada);
        $pointOfSale->isAssisted = true;
        $pointOfSale->city = 'Granada';
        $pointOfSale->address = 'Paseo Violón (del) 2. Local 4.';
        $pointOfSale->zipCode = '18006';
        $pointOfSale->openingHours = 'De lunes a viernes de 10 a 20';
        $pointOfSale->ticketSalesEnabled = true;
        $pointOfSale->ticketPickUpEnabled = true;
        $pointOfSale->serviceServedEnabled = true;
        $pointOfSale->creditCardEnabled = true;
        $pointOfSale->save();

        // $pointOfSale->accounts()->attach($account);

        $pointOfSale = new PointOfSale();
        $pointOfSale->name = 'Kiosko Redentradas';
        $pointOfSale->webName = 'Kiosko Redentradas';
        $pointOfSale->ticketName = 'Kiosko Redentradas';
        $pointOfSale->province()->associate($granada);
        $pointOfSale->isAssisted = true;
        $pointOfSale->city = 'Granada';
        $pointOfSale->address = 'Calle Acera del Casino, 9.';
        $pointOfSale->zipCode = '18009';
        $pointOfSale->openingHours = 'Abierto todos los días 24 horas';
        $pointOfSale->ticketSalesEnabled = true;
        $pointOfSale->ticketPickUpEnabled = true;
        $pointOfSale->serviceServedEnabled = true;
        $pointOfSale->creditCardEnabled = true;
        $pointOfSale->cashEnabled = true;
        $pointOfSale->save();

        $pointOfSale = new PointOfSale();
        $pointOfSale->name = 'Central';
        $pointOfSale->webName = 'Central';
        $pointOfSale->ticketName = 'Central';
        $pointOfSale->province()->associate($granada);
        $pointOfSale->isAssisted = true;
        $pointOfSale->city = 'Centro';
        $pointOfSale->address = 'Calle Centro, 3.';
        $pointOfSale->zipCode = '18004';
        $pointOfSale->openingHours = 'Abierto todos los días 24 horas';
        $pointOfSale->ticketSalesEnabled = true;
        $pointOfSale->ticketPickUpEnabled = true;
        $pointOfSale->serviceServedEnabled = true;
        $pointOfSale->creditCardEnabled = true;
        $pointOfSale->cashEnabled = true;
        $pointOfSale->save();

        // $pointOfSale->accounts()->attach($account);

        $pointOfSale = new PointOfSale();
        $pointOfSale->name = 'Alm. Mediterráneo';
        $pointOfSale->webName = 'Alm. Mediterráneo';
        $pointOfSale->ticketName = 'Alm. Mediterráneo';
        $pointOfSale->province()->associate($almeria);
        $pointOfSale->isAssisted = true;
        $pointOfSale->city = 'Almería';
        $pointOfSale->address = 'Avenida Mediterráneo, 3.';
        $pointOfSale->zipCode = '13004';
        $pointOfSale->openingHours = 'Abierto todos los días 24 horas';
        $pointOfSale->ticketSalesEnabled = true;
        $pointOfSale->ticketPickUpEnabled = true;
        $pointOfSale->serviceServedEnabled = true;
        $pointOfSale->creditCardEnabled = true;
        $pointOfSale->cashEnabled = true;
        $pointOfSale->save();
    }
}
