<?php

use App\Client;
use App\Order;
use App\PaymentAttempt;
use App\PointOfSale;
use App\SessionAreaFare;
use App\SessionSeat;
use App\Ticket;
use App\TicketSeason;
use App\TicketSeasonOrder;
use Illuminate\Database\Seeder;

class TicketSeasonOrdersTableSeeder extends Seeder
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
        $pointOfSale = PointOfSale::whereName('Redentradas')->first();
        $ticketSeason = TicketSeason::first();
        if ($ticketSeason) {
            $amount = 0;
            $sessions = $ticketSeason->sessions;

            foreach ($sessions as $session) {
                $amount += 20;
            }
            $order = new Order();
            $order->account()->associate($ticketSeason->accountId);
            $order->pointOfSale()->associate($pointOfSale);
            $order->client()->associate($client);
            $order->type = 'season';
            $order->amount = $amount;
            $order->save();

            $ticketSeasonOrder = new TicketSeasonOrder();
            $ticketSeasonOrder->order()->associate($order);
            $ticketSeasonOrder->ticketSeason()->associate($ticketSeason);
            $ticketSeasonOrder->save();
            $ticketSeasonOrder->sessions()->sync($sessions);

            foreach ($sessions as $session) {
                $sessionSeat = SessionSeat::whereSessionId($session->id)->where('status', 'enabled')->first();
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
            }

            $paymentAttempt = new PaymentAttempt();
            $paymentAttempt->order()->associate($order);
            $paymentAttempt->status = 'successful';
            $paymentAttempt->amount = $amount;
            $paymentAttempt->authorizationCode = str_shuffle('BSD12321G0SD');
            $paymentAttempt->redsysNumber = random_int(100000000, 999999999);
            $paymentAttempt->save();
        }
    }
}
