<?php

namespace old;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SeasonOrderSessionsTableSeeder extends OldTableSeeder
{
    private $ticketSeasonOrders;
    private $locators;
    private $sessions;

    public function prepareRecords(): void
    {
        $this->ticketSeasonOrders = [];
        $ticketSeasonOrdersResults = DB::table('TicketSeasonOrder')
            ->leftJoin('Order', 'TicketSeasonOrder.orderId', '=', 'Order.id')
            ->select('TicketSeasonOrder.id', 'Order.locator')
            ->get();
        foreach ($ticketSeasonOrdersResults as $result) {
            $this->ticketSeasonOrders[$result->id] = $result->locator;
        }
        $ticketSeasonOrdersResults = null;

        $this->locators = [];
        $locatorsResult = $this->db->table('localizadores')->select('localizador', 'id_evento')->get();
        foreach ($locatorsResult as $result) {
            $this->locators[$result->localizador] = $result->id_evento;
        }

        $this->sessions = [];
        $sessionsResult = DB::table('Session')
            ->select('id', 'oldId')
            ->get();
        foreach ($sessionsResult as $result) {
            $this->sessions[$result->oldId] = $result->id;
        }
        $sessionsResult = null;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->prepareRecords();
        $ticketSeasonOrdersToCreate = [];
        $timestamp = Carbon::now()->toISOString();
        foreach ($this->ticketSeasonOrders as $id => $locator) {
            $ticketSeasonOrderId = Uuid::uuid4()->toString();
            if (isset($this->sessions[$this->locators[$locator]])) {
                $sessionId = $this->sessions[$this->locators[$locator]];
                $ticketSeasonOrder = [
                    "id" => $ticketSeasonOrderId,
                    "createdAt" => $timestamp,
                    "updatedAt" => $timestamp,
                    "ticketSeasonOrderId" => $id,
                    "sessionId" => $sessionId,
                ];
                $ticketSeasonOrdersToCreate[] = $ticketSeasonOrder;
            }
        }
        $this->chunkInsert('TicketSeasonOrderSession', 1000, $ticketSeasonOrdersToCreate);
    }
}
