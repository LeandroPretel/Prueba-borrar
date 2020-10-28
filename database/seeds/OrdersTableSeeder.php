<?php

use App\Client;
use App\Order;
use App\PaymentAttempt;
use App\PointOfSale;
use App\SessionAreaFare;
use App\SessionSeat;
use App\Show;
use App\Ticket;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $client = Client::first();
        $shows = Show::all();
        $shows = $shows->shuffle();
        /** @var Show $show */
        $show = $shows->get(0);
        $pointOfSale = PointOfSale::whereName('Redentradas')->first();
        /*
        $offer = new Offer();
        $offer->typis = 500;
        $offer->typisBonus = 50;
        $offer->save();

        $offer2 = new Offer();
        $offer2->typis = 1500;
        $offer2->typisBonus = 100;
        $offer2->save();
        */
        if ($show) {
            /** @var \App\Session $session */
            $session = $show->sessions()->first();
            $order = new Order();
            $order->account()->associate($show->account);
            if ($session) {
                $order->pointOfSale()->associate($pointOfSale);
            }
            $order->client()->associate($client);
            $order->amount = 20;
            $order->save();

            $sessionSeat = SessionSeat::whereSessionId($session->id)->first();
            $sessionSector = $sessionSeat->sessionSector;

            $sessionAreaFare = SessionAreaFare::whereSessionAreaId($sessionSector->sessionAreaId)->first();
            $sessionAreaFare->earlyPrice = 10;
            $sessionAreaFare->earlyDistributionPrice = 10;
            $sessionAreaFare->earlyTotalPrice = 20;
            $sessionAreaFare->save();

            $ticket = new Ticket();
            $ticket->session()->associate($session);
            $ticket->order()->associate($order);
            $ticket->sessionSeat()->associate($sessionSeat);
            $ticket->sessionAreaFare()->associate($sessionAreaFare);
            $ticket->baseAmount = $sessionAreaFare->earlyPrice;
            $ticket->distributionAmount = $sessionAreaFare->earlyDistributionPrice;
            $ticket->amount = $sessionAreaFare->earlyTotalPrice;
            $ticket->save();

            $sessionSeat->status = 'sold';
            $sessionSeat->save();

            $paymentAttempt = new PaymentAttempt();
            $paymentAttempt->order()->associate($order);
            $paymentAttempt->status = 'successful';
            $paymentAttempt->amount = 20;
            $paymentAttempt->paymentMethod = 'cash';
            $paymentAttempt->save();
        }

        $shows = $shows->shuffle();
        $show = $shows->get(0);
        $pointOfSale = PointOfSale::whereName('Redentradas')->first();
        if ($show) {
            $session = $show->sessions()->first();
            $order = new Order();
            $order->account()->associate($show->account);
            if ($session) {
                $order->pointOfSale()->associate($pointOfSale);
            }
            $order->client()->associate($client);
            $order->amount = 20;
            $order->save();

            $sessionSeat = SessionSeat::whereSessionId($session->id)->orderBy('number', 'asc')->first();
            $sessionSector = $sessionSeat->sessionSector;

            $sessionAreaFare = SessionAreaFare::whereSessionAreaId($sessionSector->sessionAreaId)->first();
            $sessionAreaFare->earlyPrice = 10;
            $sessionAreaFare->earlyDistributionPrice = 10;
            $sessionAreaFare->earlyTotalPrice = 20;
            $sessionAreaFare->save();

            $ticket = new Ticket();
            $ticket->session()->associate($session);
            $ticket->order()->associate($order);
            $ticket->sessionSeat()->associate($sessionSeat);
            $ticket->sessionAreaFare()->associate($sessionAreaFare);
            $ticket->baseAmount = $sessionAreaFare->earlyPrice;
            $ticket->distributionAmount = $sessionAreaFare->earlyDistributionPrice;
            $ticket->amount = $sessionAreaFare->earlyTotalPrice;
            $ticket->save();

            $sessionSeat->status = 'sold';
            $sessionSeat->save();

            $paymentAttempt = new PaymentAttempt();
            $paymentAttempt->order()->associate($order);
            $paymentAttempt->status = 'successful';
            $paymentAttempt->amount = 20;
            $paymentAttempt->authorizationCode = str_shuffle('BSD12321G0SD');
            $paymentAttempt->redsysNumber = random_int(100000000, 999999999);
            $paymentAttempt->save();
        }
    }
}
