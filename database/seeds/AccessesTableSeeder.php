<?php

use App\Access;
use App\Ticket;
use Illuminate\Database\Seeder;

class AccessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::with('session', 'order.client')->first();

        if ($ticket) {
            $access = new Access();
            $access->status = 'successful';
            $access->ticket()->associate($ticket);
            $access->session()->associate($ticket->session);
            $access->sessionSeat()->associate($ticket->sessionSeatId);
            $access->client()->associate($ticket->order->client);
            $access->save();
        }
    }
}
