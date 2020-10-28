<?php

namespace old;

use App\PointOfSale;
use Carbon\Carbon;
use DB;
use Ramsey\Uuid\Uuid;

class SessionSeatsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'eventos_localidades';
    protected $defaultOrder = 'localizador';
    // protected $maxInserts = 50000;

    private $sessions;
    private $sessionAreas;
    private $sessionSectors;
    private $ticketTypes = [
        1 => "normal",
        2 => "hard-ticket",
        3 => "home-ticket",
        4 => "season"
    ];
    private $locators;
    private $seasonLocators;
    private $pointsOfSale;
    private $fares;
    private $sessionAreaFares;
    private $clients;
    private $defaultPointOfSale;
    private $orderLocators;
    private $ticketSeasons;

    public function run(): void
    {
        $this->massiveImportUnchunked();
    }

    public function prepareRecords(): void
    {
        echo "Preparando registros...\n";
        $sessionsResult = DB::table("Session")->select('Session.id as id', 'Session.oldId as oldId', 'Show.accountId as accountId')
            ->leftJoin('Show', 'Session.showId', '=', 'Show.id')->get();
        foreach ($sessionsResult as $session) {
            $this->sessions[$session->oldId] = ["id" => $session->id, "accountId" => $session->accountId];
        }
        $sessionsResult = null;

        $sessionAreasResult = DB::table('SessionArea')->select('id', 'sessionId', 'oldId')->get();
        foreach ($sessionAreasResult as $sessionArea) {
            $this->sessionAreas[$sessionArea->sessionId . $sessionArea->oldId] = $sessionArea->id;
        }
        $sessionAreasResult = null;

        $sessionSectorResult = DB::table('SessionSector')->select('id', 'sessionId', 'sessionAreaId', 'oldId')->get();
        foreach ($sessionSectorResult as $sessionSector) {
            $this->sessionSectors[$sessionSector->sessionId . $sessionSector->sessionAreaId . $sessionSector->oldId] = $sessionSector->id;
        }
        $sessionSectorResult = null;

        $this->locators = [];
        $results = $this->db->table('localizadores')->select(["importe_total_compra", "id_abono", "pedido", "fecha_venta_utc", "localizador", "es_venta_telefonica", "es_taquilla", "es_abono"])->get();
        foreach ($results as $result) {
            $this->locators[$result->localizador] = [
                "importe_total_compra" => $result->importe_total_compra,
                "pedido" => $result->pedido,
                "fecha_venta_utc" => $result->fecha_venta_utc,
                "es_venta_telefonica" => $result->es_venta_telefonica,
                "es_taquilla" => $result->es_taquilla,
            ];
            if ($result->es_abono) {
                $this->seasonLocators[$result->localizador] = [
                    "id_abono" => $result->id_abono,
                    "importe_total_compra" => $result->importe_total_compra,
                    "pedido" => $result->pedido,
                    "fecha_venta_utc" => $result->fecha_venta_utc,
                    "es_venta_telefonica" => $result->es_venta_telefonica,
                    "es_taquilla" => $result->es_taquilla,
                ];
            }
        }
        $results = null;

        $pointsOfSaleResults = DB::table('PointOfSale')->select('id', 'oldId')->get();
        foreach ($pointsOfSaleResults as $pointsOfSaleResult) {
            $this->pointsOfSale[$pointsOfSaleResult->oldId] = $pointsOfSaleResult->id;
        }
        $pointsOfSaleResults = null;

        $faresResults = DB::table('Fare')->select('id', 'oldId', 'sessionId')->get();
        foreach ($faresResults as $faresResult) {
            $this->fares[$faresResult->oldId . $faresResult->sessionId] = $faresResult->id;
        }
        $faresResults = null;

        $clientsResults = DB::table('Client')->select('id', 'oldId')->get();
        foreach ($clientsResults as $clientsResult) {
            $this->clients[$clientsResult->oldId] = $clientsResult->id;
        }
        $clientsResults = null;

        $sessionAreaFareResults = DB::table('SessionAreaFare')->select('id', 'sessionAreaId', 'fareId')->get();
        foreach ($sessionAreaFareResults as $sessionAreaFareResult) {
            $this->sessionAreaFares[$sessionAreaFareResult->sessionAreaId . $sessionAreaFareResult->fareId] = $sessionAreaFareResult->id;
        }
        $sessionAreaFareResults = null;

        $ticketSeasonResults = DB::table('TicketSeason')->select('id', 'oldId')->get();
        foreach ($ticketSeasonResults as $ticketSeasonResult) {
            $this->ticketSeasons[$ticketSeasonResult->oldId] = $ticketSeasonResult->id;
        }
        $ticketSeasonResults = null;

        $this->defaultPointOfSale = PointOfSale::whereName('Redentradas')->first()->id;
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        echo "Calculando filas a insertar...\n";
        $orderKeys = [
            "id",
            "createdAt",
            "updatedAt",
            "accountId",
            "pointOfSaleId",
            "clientId",
            "locator",
            "type",
            "via",
            "amount",
            "amountPaid",
            "amountPending",
            "status",
            "observations",
        ];

        $paymentAttemptKeys = [
            "id",
            "createdAt",
            "updatedAt",
            "orderId",
            "status",
            "amount",
            "paymentMethod",
            "authorizationCode",
            "redsysNumber",
        ];

        $sessionSeatKeys = [
            "id",
            "createdAt",
            "updatedAt",
            "sessionId",
            "sessionSectorId",
            "fareId",
            "row",
            "column",
            "rowName",
            "number",
            "isForDisabled",
            "reducedVisibility",
            "status",
            "observations",
            "oldId",
        ];

        $ticketKeys = [
            "id",
            "createdAt",
            "updatedAt",
            "sessionId",
            "orderId",
            "sessionSeatId",
            "sessionAreaFareId",
            "locator",
            "number",
            "baseAmount",
            "baseAmountWithDiscount",
            "distributionAmount",
            "distributionAmountWithDiscount",
            "amount",
            "amountWithDiscount",
        ];

        $ticketSeasonOrderKeys = [
            "id",
            "createdAt",
            "updatedAt",
            "orderId",
            "ticketSeasonId",
        ];

        $timestamp = Carbon::now()->toISOString();

        $ordersToCreate = [];
        $sessionSeatsToCreate = [];
        $ticketsToCreate = [];
        $ticketSeasonOrderToCreate = [];
        $paymentAttemptsToCreate = [];

        foreach ($results as $result) {
            $sessionId = (isset($this->sessions[$result->id_evento])) ? $this->sessions[$result->id_evento]["id"] : null;
            $sessionAreaId = $this->sessionAreas[$sessionId . $result->id_area_evento] ?? null;
            $sessionSectorId = $this->sessionSectors[$sessionId . $sessionAreaId . $result->id_zona_evento] ?? null;

            if (!$sessionId || !$sessionAreaId || !$sessionSectorId) {
                echo "Error con valores de sesión S:$result->id_evento SA:$result->id_area_evento SS:$result->id_zona_evento\n";
            } else {
                if ($result->localizador) {
                    $orderType = $this->ticketTypes[$result->id_tipo_entrada];

                    // En caso de que el order exista, lo utilizamos, no lo volvemos a crear.
                    if (isset($this->orderLocators[$result->localizador])) {
                        $orderId = $this->orderLocators[$result->localizador];
                    } else {
                        $this->orderLocators = [];
                        $orderId = Uuid::uuid4()->toString();
                        $this->orderLocators[$result->localizador] = $orderId;
                        $pointOfSaleId = $this->pointsOfSale[$result->id_punto_venta] ?? $this->defaultPointOfSale;
                        if (isset($this->locators[$result->localizador])) {
                            $amount = $this->locators[$result->localizador]["importe_total_compra"];
                        } else {
                            $amount = ($result->precio_entrada) ? $result->precio_entrada : 0;
                        }

                        $clientId = $this->clients[$result->id_cliente] ?? null;
                        $via = "web";
                        if ($this->locators[$result->localizador]["es_venta_telefonica"]) {
                            $via = "phone";
                        }
                        if ($this->locators[$result->localizador]["es_taquilla"]) {
                            $via = "assisted";
                        }
                        // Creación de orders
                        $order = [
                            $orderId,
                            $timestamp,
                            $timestamp,
                            $this->sessions[$result->id_evento]["accountId"],
                            $pointOfSaleId,
                            $clientId,
                            $result->localizador,
                            $orderType,
                            $via,
                            $amount,
                            $amount,
                            0,
                            "paid",
                            $result->id_reserva,
                        ];
                        $ordersToCreate[] = $order;

                        if (isset($this->seasonLocators[$result->localizador])) {
                            $ticketSeasonId = $this->ticketSeasons[$this->seasonLocators[$result->localizador]["id_abono"]];
                            $ticketSeasonOrderId = Uuid::uuid4()->toString();
                            $ticketSeasonOrder = [
                                "id" => $ticketSeasonOrderId,
                                "createdAt" => $timestamp,
                                "updatedAt" => $timestamp,
                                "orderId" => $orderId,
                                "ticketSeasonId" => $ticketSeasonId
                            ];
                            $ticketSeasonOrderToCreate[] = $ticketSeasonOrder;
                        }

                        $paymentCreatedAt = $result->fecha_venta_utc;
                        $redsysNumber = null;
                        if (isset($this->reservations[$result->id_reserva])) {
                            $paymentCreatedAt = $this->locators[$result->localizador]["fecha_venta_utc"];
                            $redsysNumber = $this->locators[$result->localizador]["pedido"];
                        }
                        $paymentCreatedAt = Carbon::parse($paymentCreatedAt);

                        $paymentAttemptId = Uuid::uuid4()->toString();
                        $paymentAttempt = [
                            $paymentAttemptId,
                            $paymentCreatedAt,
                            $timestamp,
                            $orderId,
                            "successful",
                            $amount,
                            "card",
                            "",
                            $redsysNumber,
                        ];
                        $paymentAttemptsToCreate[] = $paymentAttempt;
                    }

                    $row = ($result->numerada) ? $result->coord_y : null;
                    $column = ($result->numerada) ? $result->coord_x : null;
                    $rowName = ($result->numerada) ? $result->fila : null;
                    $number = ($result->numerada) ? $result->butaca : null;
                    $status = ($result->estado === 0) ? "enabled" : "sold";
                    $oldId = $result->id_evento . $result->num_entrada;
                    $seatId = Uuid::uuid4()->toString();
                    $fareId = $this->fares[$result->id_tarifa_evento . $sessionId];

                    $sessionSeat = [
                        $seatId,
                        $timestamp,
                        $timestamp,
                        $sessionId,
                        $sessionSectorId,
                        $fareId,
                        $row,
                        $column,
                        $rowName,
                        $number,
                        ($result->accesible_discapacitados) ? 1 : 0,
                        ($result->visibilidad_reducida) ? 1 : 0,
                        $status,
                        implode(' ,', [$result->butaca, $result->num_entrada]),
                        $oldId,
                    ];
                    $sessionSeatsToCreate[] = $sessionSeat;

                    if ($status === "sold") {
                        $ticketId = Uuid::uuid4()->toString();

                        $distribution = $result->precio_distr ?: 0;
                        $amount = $result->precio_entrada ?: 0;

                        $ticket = [
                            $ticketId,
                            $timestamp,
                            $timestamp,
                            $sessionId,
                            $orderId,
                            $seatId,
                            $this->sessionAreaFares[$this->sessionAreas[$sessionId . $result->id_area_evento] . $fareId],
                            $result->localizador,
                            $result->num_entrada,
                            $amount - $distribution,
                            $amount - $distribution,
                            $distribution,
                            $distribution,
                            $amount,
                            $amount,
                        ];
                        $ticketsToCreate[] = $ticket;
                    }
                } else {
                    $seatId = Uuid::uuid4()->toString();

                    $sessionSeat = [
                        $seatId,
                        $timestamp,
                        $timestamp,
                        $sessionId,
                        $sessionSectorId,
                        null, // NOT ASSOCIATING FARE
                        $result->coord_y,
                        $result->coord_x,
                        $result->fila,
                        $result->butaca,
                        ($result->accesible_discapacitados) ? 1 : 0,
                        ($result->visibilidad_reducida) ? 1 : 0,
                        "deleted",
                        implode(' ,', [$result->butaca, $result->num_entrada]),
                        $result->id_evento . $result->num_entrada,
                    ];
                    $sessionSeatsToCreate[] = $sessionSeat;
                }
            }
        }
        echo "En total se generarán " . (count($ordersToCreate) + count($sessionSeatsToCreate) + count($ticketsToCreate) + count($ticketSeasonOrderToCreate) + count($paymentAttemptsToCreate)) . " filas\n";

        $this->massiveInsertToCSVNonBlocking('Order', $orderKeys, $ordersToCreate);
        $ordersToCreate = null;
        $this->massiveInsertToCSVNonBlocking('SessionSeat', $sessionSeatKeys, $sessionSeatsToCreate);
        $sessionSeatsToCreate = null;
        $this->massiveInsertToCSVNonBlocking('Ticket', $ticketKeys, $ticketsToCreate);
        $ticketsToCreate = null;
        $this->massiveInsertToCSVNonBlocking('TicketSeasonOrder', $ticketSeasonOrderKeys, $ticketSeasonOrderToCreate);
        $ticketSeasonOrderToCreate = null;
        $this->massiveInsertToCSV('PaymentAttempt', $paymentAttemptKeys, $paymentAttemptsToCreate);
        $paymentAttemptsToCreate = null;
    }
}
