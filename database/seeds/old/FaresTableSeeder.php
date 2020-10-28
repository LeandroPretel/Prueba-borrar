<?php

namespace old;

use Carbon\Carbon;
use DB;
use Ramsey\Uuid\Uuid;

class FaresTableSeeder extends OldTableSeeder
{
    protected $tableName = 'tarifas_eventos';
    protected $defaultOrder = 'id_tarifa_evento';

    private $sessions;
    private $seasons;
    private $sessionAreas;
    private $preciosEventos;

    public function run(): void
    {
        $this->massiveImport(10000);
    }

    public function prepareRecords(): void
    {
        $sessionsResult = DB::table("Session")->select('id', 'oldId')->get();
        $this->sessions = [];
        foreach ($sessionsResult as $session) {
            $this->sessions[$session->oldId] = $session->id;
        }

        $sessionsResult = null;
        $seasonsResult = DB::table("TicketSeason")->select('id', 'oldId')->get();
        foreach ($seasonsResult as $season) {
            $this->seasons[$season->oldId] = $season->id;
        }

        $seasonsResult = null;
        $sessionAreasResult = DB::table('SessionArea')->select('id', 'sessionId', 'oldId')->get();
        foreach ($sessionAreasResult as $sessionArea) {
            $this->sessionAreas[$sessionArea->sessionId . $sessionArea->oldId] = $sessionArea->id;
        }

        $sessionAreasResult = null;
        $pricesResult = $this->db->table('precios_eventos')
            ->select('id_evento', 'id_area_evento', 'id_tarifa_evento', 'precio_entrada', 'precio_distr', 'precio_total', 'precio_entrada_taquilla', 'precio_distr_taquilla', 'precio_total_taquilla')
            ->get();
        foreach ($pricesResult as $price) {
            $precio = [
                "id_area_evento" => $price->id_area_evento,
                "precio_entrada" => $price->precio_entrada,
                "precio_distr" => $price->precio_distr,
                "precio_total" => $price->precio_total,
                "precio_entrada_taquilla" => $price->precio_entrada_taquilla,
                "precio_distr_taquilla" => $price->precio_distr_taquilla,
                "precio_total_taquilla" => $price->precio_total_taquilla
            ];
            /** @noinspection UnSafeIsSetOverArrayInspection */
            if (!isset($this->preciosEventos[$price->id_evento . $price->id_tarifa_evento])) {
                $this->preciosEventos[$price->id_evento . $price->id_tarifa_evento] = [];
            }
            $this->preciosEventos[$price->id_evento . $price->id_tarifa_evento][] = $precio;
        }
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        $faresToCreate = [];
        $sessionAreaFaresToCreate = [];
        $sessionDefaultFaresToUpdate = [];

        $timestamp = Carbon::now()->toISOString();

        foreach ($results as $result) {
            $uuid = Uuid::uuid4()->toString();
            $restrictionStartDate = null;
            $restrictionEndDate = null;
            if ($result->validez_desde_utc && $result->validez_hasta_utc) {
                $restrictionStartDate = Carbon::parse($result->validez_desde_utc);
                $restrictionEndDate = Carbon::parse($result->validez_hasta_utc);
            }

            $seasonId = null;

            if ($result->es_tarifa_abono && $result->id_abono) {
                $seasonId = $this->seasons[$result->id_abono];
            }

            if ($result->es_tarifa_default) {
                $sessionDefaultFaresToUpdate[$this->sessions[$result->id_evento]] = $uuid;
            }

            $fare = [
                "id" => $uuid,
                "createdAt" => $timestamp,
                "updatedAt" => $timestamp,
                "deletedAt" => null,
                "createdBy" => null,
                "updatedBy" => null,
                "deletedBy" => null,
                "sessionId" => $this->sessions[$result->id_evento],
                "name" => $result->nombre,
                "webName" => $result->nombre,
                "ticketName" => $result->nombre,
                "description" => $result->mensaje_entrada,
                "webDescription" => $result->mensaje_web,
                "checkIdentity" => $result->comprobar_identidad_portero,
                "checkIdentityMessage" => $result->mensaje_comprobar_identidad_portero,
                "assistedPointOfSaleMessage" => $result->mensaje_kiosco_asistido,
                "minTicketsByOrder" => $result->minimo_entradas_compra,
                "maxTicketsByOrder" => $result->maximo_entradas_compra,
                "maxTickets" => $result->cupo_maximo,
                "restrictionStartDate" => $restrictionStartDate,
                "restrictionEndDate" => $restrictionEndDate,
                "associatedToTuPalacio" => false,
                "isPromotion" => $result->es_tarifa_promocion,
                "isSeason" => $result->es_tarifa_abono,
                "observations" => "",
                "ticketSeasonId" => $seasonId,
                "oldId" => $result->id_tarifa_evento,
            ];
            $faresToCreate[] = $fare;

            foreach ($this->preciosEventos[$result->id_evento . $result->id_tarifa_evento] as $precioEvento) {
                $sessionAreaFareId = Uuid::uuid4();
                $sessionAreaFare = [
                    "id" => $sessionAreaFareId,
                    "createdAt" => $timestamp,
                    "updatedAt" => $timestamp,
                    "createdBy" => null,
                    "updatedBy" => null,
                    "sessionAreaId" => $this->sessionAreas[$this->sessions[$result->id_evento] . $precioEvento["id_area_evento"]],
                    "fareId" => $uuid,
                    "isActive" => true,
                    "earlyPrice" => $precioEvento["precio_entrada"],
                    "earlyDistributionPrice" => $precioEvento["precio_distr"],
                    "earlyTotalPrice" => $precioEvento["precio_total"],
                    "ticketOfficePrice" => $precioEvento["precio_entrada_taquilla"],
                    "ticketOfficeDistributionPrice" => $precioEvento["precio_distr_taquilla"],
                    "ticketOfficeTotalPrice" => $precioEvento["precio_total_taquilla"],
                ];
                $sessionAreaFaresToCreate[] = $sessionAreaFare;
            }
        }

        $this->chunkInsert('Fare', 1000, $faresToCreate);
        $faresToCreate = null;
        $this->chunkInsert('SessionAreaFare', 1000, $sessionAreaFaresToCreate);
        $sessionAreaFaresToCreate = null;

        foreach ($sessionDefaultFaresToUpdate as $sessionId => $fareId) {
            DB::table('Session')->where('id', $sessionId)->update(["defaultFareId" => $fareId]);
        }
    }
}
