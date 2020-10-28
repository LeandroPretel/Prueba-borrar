<?php

use App\Client;
use App\Order;
use App\PaymentAttempt;
use App\PointOfSale;
use App\SessionAreaFare;
use App\SessionSeat;
use App\Ticket;
use App\TicketVoucher;
use App\TicketVoucherOrder;
use Illuminate\Database\Seeder;

class TicketVoucherOrdersTableSeeder extends Seeder
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
        $ticketVoucher = TicketVoucher::first();
        if ($ticketVoucher) {
            $sessions = $ticketVoucher->sessions;
            $amount = 0;
            foreach ($sessions as $session) {
                $amount += 20;
            }
            $order = new Order();
            $order->account()->associate($ticketVoucher->accountId);
            $order->pointOfSale()->associate($pointOfSale);
            $order->client()->associate($client);
            $order->type = 'voucher';
            $order->amount = $amount;
            $order->save();

            $ticketVoucherOrder = new TicketVoucherOrder();
            $ticketVoucherOrder->order()->associate($order);
            $ticketVoucherOrder->ticketVoucher()->associate($ticketVoucher);
            $ticketVoucherOrder->save();
            $ticketVoucherOrder->sessions()->sync($sessions);

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
