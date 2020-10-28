<?php

namespace old;

use Carbon\Carbon;
use DB;
use Ramsey\Uuid\Uuid;

class SessionsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'eventos';
    protected $defaultOrder = 'id_evento';
    // protected $maxInserts = 1000;

    private $sessions;
    private $shows;
    private $places;
    private $spaces;
    private $showTemplates;
    private $partners;
    private $enterprises;
    private $observations;

    private $tiposIva = [
        0 => 0,
        1 => 21,
        2 => 10,
        3 => 8,
        4 => 21
    ];

    public function prepareRecords(): void
    {
        $this->sessions = [];
        $sessionResults = DB::table('Session')->select('id', 'oldId')->get();
        foreach ($sessionResults as $result) {
            $this->sessions[$result->oldId] = $result->id;
        }
        $sessionResults = null;

        $this->shows = [];
        $showsResults = DB::table('Show')->select('id', 'oldId')->get();
        foreach ($showsResults as $result) {
            $this->shows[$result->oldId] = $result->id;
        }
        $showsResults = null;

        $this->places = [];
        $placesResults = DB::table('Place')->select('id', 'oldId')->get();
        foreach ($placesResults as $result) {
            $this->places[$result->oldId] = $result->id;
        }
        $placesResults = null;

        $this->spaces = [];
        $spacesResults = DB::table('Space')->select('id', 'oldId', 'placeId')->get();
        foreach ($spacesResults as $result) {
            $this->spaces[$result->oldId . $result->placeId] = $result->id;
        }
        $spacesResults = null;

        $this->showTemplates = [];
        $showTemplatesResult = DB::table('ShowTemplate')->select('id', 'oldId')->get();
        foreach ($showTemplatesResult as $result) {
            $this->showTemplates[$result->oldId] = $result->id;
        }
        $showTemplatesResult = null;

        $this->partners = [];
        $partnersResult = DB::table('Partner')->select('id', 'oldId', 'commissionPercentage', 'commissionMinimum', 'commissionMaximum')->get();
        foreach ($partnersResult as $result) {
            $this->partners[$result->oldId] = [
                "id" => $result->id,
                "commissionPercentage" => $result->commissionPercentage,
                "commissionMinimum" => $result->commissionMinimum,
                "commissionMaximum" => $result->commissionMaximum,
            ];
        }
        $partnersResult = null;

        $this->enterprises = [];
        $enterprisesResult = DB::table('Enterprise')->select('id', 'oldId')->get();
        foreach ($enterprisesResult as $result) {
            $this->enterprises[$result->oldId] = $result->id;
        }
        $enterprisesResult = null;

        $this->observations = [];
        $observationsResult = $this->db->table('observaciones')->select('id_evento', 'texto')->get();
        foreach ($observationsResult as $result) {
            $this->observations[$result->id_evento] = $result->texto;
        }
        $observationsResult = null;
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        $sessionsToUpdate = [];
        $sessionsToCreate = [];
        $partnerSessionsToCreate = [];
        $savitarFilesToCreate = [];
        $timestamp = Carbon::now()->toISOString();

        foreach ($results as $result) {
            //dd($result);
            $sessionId = $this->sessions[$result->id_evento] ?? null;
            $showId = $this->shows[$result->id_contrato];
            $placeId = $this->places[$result->id_recinto] ?? null;
            $showTemplateId = $this->showTemplates[$result->id_espectaculo];
            if ($placeId) {
                $spaceId = $this->spaces[$result->id_aforo . $placeId] ?? null;
                if ($spaceId) {
                    $isActive = $result->activo ? true : false;
                    $status = $result->cancelado ? 'Cancelada' : 'A la venta';
                    $date = $result->fecha_evento ? Carbon::parse($result->fecha_evento) : null;
                    $session = [
                        "createdAt" => $timestamp,
                        "updatedAt" => $timestamp,
                        "showId" => $showId,
                        "placeId" => $placeId,
                        "spaceId" => $spaceId,
                        "showTemplateId" => $showTemplateId,
                        "isActive" => $isActive,
                        "status" => $status,
                        "isHidden" => false,
                        "date" => $date,
                        "openingDoorsDate" => null,
                        "closingDoorsDate" => null,
                        "vat" => $this->tiposIva[$result->id_tipo_iva],
                        "defaultFareId" => null,
                    ];

                    $session["automaticDistributionPercentage"] = $result->gd_porcentaje;
                    $session["automaticDistributionMinimum"] = $result->gd_minimo;
                    $session["automaticDistributionMaximum"] = $result->gd_maximo;
                    $session["displayAsSoon"] = $result->proximamente;
                    $session["assistedSellEndDate"] = $result->fecha_cierre_kioscos ? Carbon::parse($result->fecha_cierre_kioscos) : null;

                    $session["advanceSaleEnabled"] = $result->fecha_inicio_venta_anticipada && $result->fecha_fin_venta_anticipada ? $result->admite_venta_anticipada : false;
                    $session["advanceSaleStartDate"] = $result->fecha_inicio_venta_anticipada ? Carbon::parse($result->fecha_inicio_venta_anticipada) : null;
                    $session["advanceSaleFinishDate"] = $result->fecha_fin_venta_anticipada ? Carbon::parse($result->fecha_fin_venta_anticipada) : null;

                    $session["pickUpInPointsOfSaleEnabled"] = $result->admite_recogida ?: false;
                    $session["pickUpInPointsOfSaleStartDate"] = $result->admite_recogida ? Carbon::parse($result->fecha_inicio_recogidas) : null;
                    $session["pickUpInPointsOfSaleEndDate"] = $result->admite_recogida ? Carbon::parse($result->fecha_fin_recogidas) : null;

                    $session["ticketOfficesEnabled"] = $result->admite_recogida_taquilla ?: false;
                    $session["ticketOfficesStartDate"] = $result->admite_recogida_taquilla ? Carbon::parse($result->fecha_apertura_taquillas) : null;
                    $session["ticketOfficesEndDate"] = $result->admite_recogida_taquilla ? Carbon::parse($result->fecha_cierre_taquillas) : null;

                    $session["openingDoorsDate"] = ($result->fecha_puertas) ? Carbon::parse($result->fecha_puertas) : null;

                    $session["isActive"] = ($result->activo) ? true : false;

                    $session["pointOfSaleCommissionPercentage"] = $result->forzar_com_vendedor_porcentaje ? $result->com_vendedor_porcentaje : null;
                    $session["pointOfSaleCommissionMinimum"] = $result->forzar_com_vendedor_minimo ? $result->com_vendedor_minimo : null;
                    $session["pointOfSaleCommissionMaximum"] = $result->forzar_com_vendedor_maximo ? $result->com_vendedor_maximo : null;


                    $session["printCommissionPercentage"] = $result->forzar_com_impresion_porcentaje ? $result->com_impresion_porcentaje : null;
                    $session["printCommissionMinimum"] = $result->forzar_com_impresion_minimo ? $result->com_impresion_minimo : null;
                    $session["printCommissionMaximum"] = $result->forzar_com_impresion_maximo ? $result->com_impresion_maximo : null;

                    $session["returnExpensesWhenCancelled"] = $result->cancelado_devolver_gastos;

                    $session["isExternal"] = $result->es_externo;
                    $session["externalUrl"] = $result->url_externa;
                    $session["externalEnterpriseId"] = $result->id_empresa_externa ? $this->enterprises[$result->id_empresa_externa] : null;
                    $session["isLiquidated"] = $result->liquidado;
                    $session["observations"] = $this->observations[$result->id_evento] ?? null;

                    $fileId = Uuid::uuid4()->toString();
                    $savitarFile = [
                        "id" => $fileId,
                        "fileableId" => $spaceId,
                        "fileableType" => 'App\Space',
                        "name" => $result->imagesource,
                        "path" => 'https://www.redentradas.com/img/aforos/' . $result->imagesource,
                        "url" => 'https://www.redentradas.com/img/aforos/' . $result->imagesource,
                        "category" => "mainImage",
                        "extension" => "png",
                        "createdAt" => $timestamp,
                        "updatedAt" => $timestamp,
                    ];
                    $savitarFilesToCreate[] = $savitarFile;

                    if ($sessionId) {
                        $session["id"] = $sessionId;
                        $sessionsToUpdate[] = $session;
                    } else {
                        $sessionId = Uuid::uuid4()->toString();
                        $session["id"] = $sessionId;
                        $session["oldId"] = $result->id_evento;
                        $sessionsToCreate[] = $session;
                    }

                    if ($result->id_partner && isset($this->partners[$result->id_partner])) {
                        $partner = $this->partners[$result->id_partner];
                        $partnerSessionId = Uuid::uuid4()->toString();
                        $partnerSession = [
                            "id" => $partnerSessionId,
                            "createdAt" => $timestamp,
                            "updatedAt" => $timestamp,
                            "partnerId" => $partner["id"],
                            "sessionId" => $sessionId,
                        ];

                        $partnerSession["commissionPercentage"] = ($result->forzar_com_partner_porcentaje) ? $result->com_partner_porcentaje : $partner["commissionPercentage"];
                        $partnerSession["commissionMinimum"] = ($result->forzar_com_partner_minimo) ? $result->com_partner_minimo : $partner["commissionMinimum"];
                        $partnerSession["commissionMaximum"] = ($result->forzar_com_partner_maximo) ? $result->com_partner_maximo : $partner["commissionMaximum"];
                        $partnerSessionsToCreate[] = $partnerSession;
                    }
                } else {
                    dump("Space ID WRONG: AFORO: $result->id_aforo PLACE: $placeId EVENTO: $result->id_evento RECINTO: $result->id_recinto\n");
                }
            }
        }

        $this->chunkInsert('Session', 1000, $sessionsToCreate);
        $this->chunkInsert('PartnerSession', 1000, $partnerSessionsToCreate);
        $this->chunkInsert('File', 1000, $savitarFilesToCreate);

        foreach ($sessionsToUpdate as $session) {
            DB::table('Session')->where('id', $session["id"])->update($session);
        }
    }

    public function run(): void
    {
        $this->massiveImport();
    }
}
