<?php

namespace old;

use App\Place;
use App\Session;
use App\Space;
use App\TicketSeason;
use App\TicketSeasonGroup;
use Carbon\Carbon;
use Exception;
use Savitar\Files\SavitarFile;

class TicketSeasonsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'abonos';
    protected $defaultOrder = 'id_abono';

    /**
     * @param $result
     * @throws Exception
     */
    public function createRecords($result): void
    {
        $ticketSeasonGroup = TicketSeasonGroup::where('oldId', $result->id_grupo_abonados)->first();
        if ($result->id_recinto) {
            /** @var Place $place */
            $place = Place::where('oldId', $result->id_recinto)->first();
            if ($place) {
                $space = Space::where('oldId', $result->id_aforo)->where('placeId', $place->id)->first();
            }
        }
        if ($place && $space && $ticketSeasonGroup) {
            // rel_eventos_abonos son las sesiones (eventos) asociadas al abono.
            $firstRelatedSession = $this->db->table('rel_eventos_abonos')->where('id_abono', $result->id_abono)->first();
            if ($firstRelatedSession) {
                /** @var Session $firstSession */
                $firstSession = Session::where('oldId', $firstRelatedSession->id_evento)->with(['show'])->first();
                if ($firstSession) {
                    $ticketSeason = new TicketSeason();
                    $ticketSeason->account()->associate($firstSession->show->accountId);
                    $ticketSeason->place()->associate($place);
                    $ticketSeason->space()->associate($space);
                    $ticketSeason->ticketSeasonGroup()->associate($ticketSeasonGroup);
                    $ticketSeason->isActive = $result->activo;
                    $ticketSeason->name = $result->nombre;
                    $ticketSeason->webName = $result->nombre_web ?: $result->nombre;
                    $ticketSeason->ticketName = $result->nombre_entrada ?: $result->nombre;
                    $ticketSeason->minSessions = $result->min_espectaculos;
                    $ticketSeason->maxSessions = $result->max_espectaculos;
                    if ($result->min_espectaculos === $result->max_espectaculos) {
                        $ticketSeason->type = 'fixed';
                    } else {
                        $ticketSeason->type = 'combined';
                    }
                    $ticketSeason->description = $result->subtitulo;
                    if ($result->renovaciones) {
                        $ticketSeason->renovationsEnabled = $result->renovaciones;
                        $ticketSeason->renovationStartDate = Carbon::parse($result->fecha_inicio_renovaciones);
                        $ticketSeason->renovationEndDate = Carbon::parse($result->fecha_fin_venta);
                    }
                    if ($result->ventas) {
                        $ticketSeason->newSalesEnabled = $result->ventas;
                        $ticketSeason->newSalesStartDate = Carbon::parse($result->fecha_inicio_venta);
                        $ticketSeason->newSalesEndDate = Carbon::parse($result->fecha_fin_venta);
                    }
                    if ($result->modificaciones) {
                        $ticketSeason->changesEnabled = $result->modificaciones;
                        $ticketSeason->changesStartDate = Carbon::parse($result->fecha_inicio_modificaciones);
                        $ticketSeason->changesEndDate = Carbon::parse($result->fecha_fin_venta);
                    }
                    if ($result->envios) {
                        $ticketSeason->shippingEnabled = $result->envios;
                        $ticketSeason->shippingStartDate = Carbon::parse($result->fecha_inicio_envios);
                        $ticketSeason->shippingEndDate = Carbon::parse($result->fecha_fin_envios);
                    }
                    $ticketSeason->printingEnabled = $result->imprimir_tarjeta;
                    $ticketSeason->oldId = $result->id_abono;
                    $ticketSeason->save();

                    $relatedSessions = $this->db->table('rel_eventos_abonos')->where('id_abono', $result->id_abono)->get();
                    $relatedSessionIds = collect();
                    foreach ($relatedSessions as $relatedSession) {
                        /** @var Session $session */
                        $session = Session::where('oldId', $relatedSession->id_evento)->first();
                        if ($session) {
                            $relatedSessionIds->put($session->id, ['required' => $relatedSession->obligatorio]);
                        }
                    }
                    $ticketSeason->sessions()->sync($relatedSessionIds);

                    $savitarFile = new SavitarFile();
                    $savitarFile->name = $result->imagen;
                    $savitarFile->url = 'https://www.redentradas.com/img/abonos/' . $result->imagen;
                    $savitarFile->path = 'https://www.redentradas.com/img/abonos/' . $result->imagen;
                    $savitarFile->extension = 'jpg';
                    $savitarFile->category = 'mainImage';
                    $ticketSeason->files()->save($savitarFile);
                }
            }
        }
    }
}
