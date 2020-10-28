<?php

use App\Account;
use App\Place;
use App\PointOfSale;
use App\TicketSeason;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Savitar\Files\SavitarFile;
use Savitar\Models\SavitarBuilder;

class TicketSeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $account = Account::where('email', 'promotor@redentradas.es')->first();
        $place = Place::whereHas('spaces', static function (SavitarBuilder $query) {
            $query->whereHas('sessions');
        })->first();
        $space = $place->spaces()->whereHas('sessions')->first();

        if ($space) {
            $ticketSeason = new TicketSeason();
            $ticketSeason->account()->associate($account);
            $ticketSeason->place()->associate($place);
            $ticketSeason->space()->associate($space);
            $ticketSeason->name = 'Abono de prueba';
            $ticketSeason->webName = 'Abono de prueba';
            $ticketSeason->ticketName = 'Abono de prueba';
            $ticketSeason->description = 'Abono de prueba';
            $ticketSeason->renovationsEnabled = true;
            $ticketSeason->renovationStartDate = Carbon::now();
            $ticketSeason->renovationEndDate = Carbon::now()->addWeeks(1);
            $ticketSeason->changesEnabled = true;
            $ticketSeason->changesStartDate = Carbon::now()->addMonths(1);
            $ticketSeason->changesEndDate = Carbon::now()->addMonths(2);
            $ticketSeason->newSalesEnabled = true;
            $ticketSeason->newSalesStartDate = Carbon::now()->addMonths(3);
            $ticketSeason->newSalesEndDate = Carbon::now()->addMonths(4);
            $ticketSeason->save();

            $ticketSeason->sessions()->sync(\App\Session::where('spaceId', $space->id)->limit(2)->get());
            $ticketSeason->pointsOfSale()->sync(PointOfSale::all());

            $file = new SavitarFile();
            $file->category = 'mainImage';
            $file->name = 'Imagen.png';
            $file->size = 2400000;
            $file->path = '';
            $file->url = 'https://images.clipartlogo.com/files/istock/previews/8047/80475683-premium-product-seal-or-icon.jpg';
            $file->extension = 'jpg';
            $file->pages = 1;
            $ticketSeason->files()->save($file);
        }
    }
}
