<?php

use App\Account;
use App\PointOfSale;
use App\TicketVoucher;
use Illuminate\Database\Seeder;
use Savitar\Files\SavitarFile;

class TicketVouchersTableSeeder extends Seeder
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

        $ticketVoucher = new TicketVoucher();
        $ticketVoucher->account()->associate($account);
        $ticketVoucher->name = 'Bono de prueba';
        $ticketVoucher->webName = 'Bono de prueba';
        $ticketVoucher->ticketName = 'Bono de prueba';
        $ticketVoucher->description = 'Bono de prueba';
        $ticketVoucher->minSessions = 2;
        $ticketVoucher->maxSessions = 4;
        $ticketVoucher->save();

        $ticketVoucher->sessions()->sync(\App\Session::limit(2)->get());
        $ticketVoucher->pointsOfSale()->sync(PointOfSale::all());

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://c8.alamy.com/compes/mk2acc/bono-pegatina-roja-diseno-plano-icono-vectorial-mk2acc.jpg';
        $file->extension = 'jpg';
        $file->pages = 1;
        $ticketVoucher->files()->save($file);
    }
}
