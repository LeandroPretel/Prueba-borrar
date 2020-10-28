<?php

namespace App\Http\Controllers;

use App\Place;
use App\Session;
use App\SessionSeat;
use App\SessionSector;
use App\TicketSeason;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Throwable;

class SessionSeatController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * SessionSeatController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Place::class);
        $this->configureCRUD([
            'modelClass' => SessionSeat::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => SessionSeat::class,
            'dataGridTitle' => 'Butacas',
        ]);
    }

    /**
     * Releases N reserved sessionSeats.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function freeSessionSeats(Request $request): JsonResponse
    {
        if ($request->has('sessionSeatIds')) {
            $enabledSeatsCount = SessionSeat::whereIn('id', $request->input('sessionSeatIds'))
                ->where('status', '=', 'reserved')
                ->update(['status' => 'enabled', 'updatedAt' => Carbon::now()]);

            return response()->json(['enabledSeatsCount' => $enabledSeatsCount], 200);
        }
        return response()->json([], 400);
    }

    /**
     * Releases N locked sessionSeats.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function freeLockedSessionSeats(Request $request): JsonResponse
    {
        if ($request->has('sessionSeatIds')) {
            $enabledSeatsCount = SessionSeat::whereIn('id', $request->input('sessionSeatIds'))
                ->where('status', '=', 'locked')
                ->update(['status' => 'enabled', 'lockReason' => null, 'updatedAt' => Carbon::now()]);

            // Check if some sessionSeats couldn't be updated, cause someone bought/reserved while doing it
            $gapCount = count($request->input('sessionSeatIds')) - $enabledSeatsCount;

            return response()->json(['enabledSeatsCount' => $enabledSeatsCount, 'gapCount' => $gapCount], 200);
        }
        return response()->json([], 400);
    }

    /**
     * Lock N sessionSeats.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lockSessionSeats(Request $request): JsonResponse
    {
        if ($request->has('sessionSeatIds')) {
            $updates = ['status' => 'locked', 'updatedAt' => Carbon::now()];
            if ($request->has('lockReason')) {
                $updates['lockReason'] = $request->input('lockReason');
            }
            $lockedSessionSeatsCount = SessionSeat::whereIn('id', $request->input('sessionSeatIds'))
                ->where('status', 'enabled')
                ->update($updates);

            // Check if some sessionSeats couldn't be updated, cause someone bought/reserved while doing it
            $gapCount = count($request->input('sessionSeatIds')) - $lockedSessionSeatsCount;

            return response()->json(['lockedSeatsCount' => $lockedSessionSeatsCount, 'gapCount' => $gapCount], 200);
        }
        return response()->json([], 400);
    }

    /**
     * Delete N sessionSeats.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteSessionSeats(Request $request): JsonResponse
    {
        if ($request->has('sessionSeatIds')) {
            $updates = ['status' => 'deleted', 'updatedAt' => Carbon::now()];

            $deletedSessionSeatsCount = SessionSeat::whereIn('id', $request->input('sessionSeatIds'))
                ->where('status', '=', 'enabled')
                ->update($updates);

            // Check if some sessionSeats couldn't be updated, cause someone bought/reserved while doing it
            $gapCount = count($request->input('sessionSeatIds')) - $deletedSessionSeatsCount;

            return response()->json(['deletedSessionSeatsCount' => $deletedSessionSeatsCount, 'gapCount' => $gapCount], 200);
        }
        return response()->json([], 400);
    }

    /**
     * Restores de sessionSeeats with status 'deleted'.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function restoreSessionSeats(Request $request): JsonResponse
    {
        $restoredSeatsCount = SessionSeat::where('sessionId', $request->input('sessionId'))
            ->where('sessionSectorId', '=', $request->input('sessionSectorId'))
            ->whereNotNull('number')
            ->where('status', '=', 'deleted')
            ->update(['status' => 'enabled', 'updatedAt' => Carbon::now()]);

        return response()->json(['restoredSeatsCount' => $restoredSeatsCount], 200);
    }

    /**
     * Changes sessionSeat status and returns all sessionSeats.
     *
     * @param Request $request
     * @param Session $session
     * @param SessionSeat $sessionSeat
     * @return JsonResponse
     */
    public function sessionSeatStatus(Request $request, Session $session, SessionSeat $sessionSeat): JsonResponse
    {
        if ($sessionSeat->sessionId === $session->id && $request->input('status') &&
            ($request->input('status') === 'enabled' || $request->input('status') === 'reserved')) {
            $changed = false;
            // reserve sessionSeat
            if ($sessionSeat->status === 'enabled' && $request->input('status') === 'enabled') {
                $sessionSeat->status = 'reserved';
                $changed = true;
            }
            // free a sessionSeat
            if ($sessionSeat->status === 'reserved' && $request->input('status') === 'reserved') {
                $sessionSeat->status = 'enabled';
                $changed = true;
            }
            if ($changed) {
                $sessionSeat->updatedBy = $request->get('email');
                $sessionSeat->save();

                $sessionSeats = $session->sessionSeats()->with('sessionSector')->get();
                $sessionSeats = $sessionSeats->filter(static function (SessionSeat $sessionSeat) {
                    return $sessionSeat->sessionSector->isNumbered;
                });
                $sessionSeats->makeHidden([
                    'sessionId', 'sessionSector', 'observations', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
                ]);

                return response()->json(['sessionSeats' => $sessionSeats], 200);
            }
        }
        return response()->json(['error' => 'Butaca no disponible.'], 409);
    }

    /**
     * Changes sessionSeat status.
     *
     * @param Request $request
     * @param $sessionId
     * @param $sessionSectorId
     * @param $row
     * @param $column
     * @return JsonResponse
     * @throws \Throwable
     */
    public function sessionSeatStatusByRowAndColumn(Request $request, $sessionId, $sessionSectorId, $row, $column): JsonResponse
    {
        if ($request->input('status') && ($request->input('status') === 'enabled' || $request->input('status') === 'reserved')) {
            DB::beginTransaction();
            /** @var SessionSeat $sessionSeat */
            $sessionSeat = DB::table('SessionSeat')
                ->where('sessionId', $sessionId)
                ->where('sessionSectorId', $sessionSectorId)
                ->where('row', $row)
                ->where('column', $column)
                ->lockForUpdate()
                ->first();

            if (!$sessionSeat) {
                DB::rollBack();
                return response()->json(['error' => 'Butaca no disponible.'], 409);
            }
            // reserve sessionSeat
            if ($sessionSeat->status === 'enabled' && $request->input('status') === 'enabled') {
                $newStatus = 'reserved';
            }
            // free a sessionSeat
            if ($sessionSeat->status === 'reserved' && $request->input('status') === 'reserved') {
                $newStatus = 'enabled';
            }
            if (isset($newStatus)) {
                DB::table('SessionSeat')->where('id', $sessionSeat->id)->update([
                    'status' => $newStatus,
                    'updatedAt' => Carbon::now(),
                    'updatedBy' => $request->get('email')
                ]);
                DB::commit();
                /*
                $sessionSeats = $session->sessionSeats()->with('sessionSector')->get();
                $sessionSeats = $sessionSeats->filter(static function (SessionSeat $sessionSeat) {
                    return $sessionSeat->sessionSector->isNumbered;
                });
                $sessionSeats->makeHidden([
                    'sessionId', 'sessionSector', 'observations', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
                ]);
                */
                return response()->json(['newStatus' => $newStatus], 200);
            }
        }
        return response()->json(['error' => 'Butaca no disponible.'], 409);
    }

    /**
     * Changes sessionSeat status for selectedSessions
     *
     * @param Request $request
     * @param $row
     * @param $column
     * @return JsonResponse
     */
    public function sessionSeatStatusSessions(Request $request, $row, $column): JsonResponse
    {
        $sessionsIdsToCheck = $request->input('sessionIds');
        $sessionSeats = SessionSeat::join(
            'SessionSector', 'SessionSector.id', '=', 'SessionSeat.sessionSectorId'
        )
            ->whereIn('SessionSeat.sessionId', $sessionsIdsToCheck)
            ->where('row', $row)
            ->where('column', $column)
            ->where('SessionSector.webName', '=', $request->input('sectorName'))
            ->select(['SessionSeat.*'])
            ->get();

        $checkSameStatus = $sessionSeats->every(function ($value) use ($sessionSeats){
            return $value->status === $sessionSeats->first()->status;
        });
        // Ya hay algun asiento que no tiene el mismo estado o algo mal
        if (!$checkSameStatus || count($sessionsIdsToCheck) !== count($sessionSeats)) {
            return response()->json(['error' => 'Butaca no disponible.'], 409);
        }
        $sessionSeatIds = $sessionSeats->pluck('id');
        /** @var SessionSeat $sessionSeatToModify */
        if ($request->input('status') &&
            ($request->input('status') === 'enabled' || $request->input('status') === 'reserved')) {
            // reserve sessionSeat
            if ($sessionSeats->first()->status === 'enabled' && $request->input('status') === 'enabled') {
                SessionSeat::whereIn('id', $sessionSeatIds)->update([
                    'status' => 'reserved',
                    'updatedAt' => Carbon::now()
                ]);
            }
            // free a sessionSeat
            if ($sessionSeats->first()->status === 'reserved' && $request->input('status') === 'reserved') {
                SessionSeat::whereIn('id', $sessionSeatIds)->update([
                    'status' => 'enabled',
                    'updatedAt' => Carbon::now()
                ]);
            }
            $sessionSeats = SessionSeat::whereIn('id', $sessionSeatIds)->get();
            return response()->json(['sessionSeats' => $sessionSeats], 200);
        }

        return response()->json(['error' => 'Butaca no disponible.'], 409);
    }

    /**
     * @param Request $request
     * @param $sessionId
     * @param $sessionSectorId
     * @return JsonResponse
     */
    public function reserveNotNumberedSeat(Request $request, $sessionId, $sessionSectorId): JsonResponse
    {
        /** @var SessionSeat $sessionSeat */
        $sessionSeat = SessionSeat::where('sessionId', $sessionId)
            ->where('sessionSectorId', $sessionSectorId)
            ->where('status', 'enabled')
            ->first();
        if ($sessionSeat) {
            $sessionSeat->status = 'reserved';
            $sessionSeat->save();

            $sessionSeat->makeHidden(['sessionId', 'observations', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy']);
            return response()->json(['sessionSeat' => $sessionSeat], 200);
        }
        return response()->json([], 409);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function reserveNotNumberedSeatSessions(Request $request): JsonResponse
    {
        $sessionsIdsToCheck = $request->input('sessionIds');
        $sessionSeats = collect();
        foreach ($sessionsIdsToCheck as $sessionId) {
            $sessionSeat = SessionSeat::join(
                'SessionSector', 'SessionSector.id', '=', 'SessionSeat.sessionSectorId'
            )
                ->where('SessionSeat.sessionId', $sessionId)
                ->where('status', 'enabled')
                ->where('SessionSector.webName', '=', $request->input('sectorName'))
                ->select(['SessionSeat.*'])
                ->first();
            if ($sessionSeat) {
                $sessionSeats->push($sessionSeat);
            }
        }
        // Alguna de las sesiones no tiene la butaca disponible
        if (count($sessionsIdsToCheck) !== count($sessionSeats)) {
            return response()->json(['error' => 'Butaca no disponible.'], 409);
        }
        $sessionSeatIds = $sessionSeats->pluck('id');
        SessionSeat::whereIn('id', $sessionSeatIds)->update([
            'status' => 'reserved',
            'updatedAt' => Carbon::now()
        ]);

        $sessionSeats = SessionSeat::whereIn('id', $sessionSeatIds)->get();
        return response()->json(['sessionSeats' => $sessionSeats], 200);
    }

    /**
     * @param Request $request
     * @param TicketSeason $ticketSeason
     * @param string $sectorName
     * @return JsonResponse
     */
    public function multiSessionsSeats(Request $request, TicketSeason $ticketSeason, string $sectorName): JsonResponse
    {
        $sessionIds = $ticketSeason->sessions()->pluck('Session.id');
        $sessionSeats = SessionSeat::with(['sessionDoors'])
            ->join(
            'SessionSector', 'SessionSector.id', '=', 'SessionSeat.sessionSectorId'
            )
            ->whereIn('SessionSeat.sessionId', $sessionIds)
            ->where('SessionSector.webName', '=', $sectorName)
            ->get();

        return \response()->json($sessionSeats, 200);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @param SessionSector $sessionSector
     * @return JsonResponse
     */
    public function notNumberedSeatsBySessionSector(Request $request, Session $session, SessionSector $sessionSector): JsonResponse
    {
        return \response()->json($sessionSector->sessionSeats->all(), 200);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @param SessionSector $sessionSector
     * @return JsonResponse
     */
    public function lockNotNumberedSeats(Request $request, Session $session, SessionSector $sessionSector): JsonResponse
    {
        $lockCount = (int)$request->get('lockSeatsCount');
        $reason = $request->get('lockReason');
        $sessionSeats = $sessionSector->sessionSeats()
            ->where('status', '=', 'enabled')
            ->limit($lockCount)
            ->get(['id']);
        $sessionSeatsIds = $sessionSeats->pluck('id');

        $count = SessionSeat::whereIn('id', $sessionSeatsIds)->update([
            'status' => 'locked',
            'lockReason' => $reason,
            'updatedAt' => Carbon::now()
        ]);

        return \response()->json(['updated' => $count], 200);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @param SessionSector $sessionSector
     * @return JsonResponse
     */
    public function unlockNotNumberedSeats(Request $request, Session $session, SessionSector $sessionSector): JsonResponse
    {
        $lockCount = (int)$request->get('unlockSeatsCount');
        $sessionSeats = $sessionSector->sessionSeats()
            ->where('status', '=', 'locked')
            ->limit($lockCount)
            ->get(['id']);
        $sessionSeatsIds = $sessionSeats->pluck('id');

        $count = SessionSeat::whereIn('id', $sessionSeatsIds)->update([
            'status' => 'enabled',
            'lockReason' => null,
            'updatedAt' => Carbon::now()
        ]);

        return \response()->json(['updated' => $count], 200);
    }

}
