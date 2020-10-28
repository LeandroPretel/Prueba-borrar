<?php

namespace App\Http\Controllers;

use App\Access;
use App\Session;
use App\SessionDoor;
use App\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class AccessController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * AccessController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Access::class);
        $this->configureCRUD([
            'modelClass' => Access::class,
            'indexJoins' => ['Client' => 'clientId'],
            'indexAttributes' => ['name', 'surname'],
            'indexAppends' => [
                'sessionSeat:id,sessionSectorId,rowName,number',
                'sessionSeat.sessionSector:id,sessionAreaId,name',
                'sessionSeat.sessionSector.sessionArea:id,name',
            ],
            'showAppends' => [
                'sessionSeat:id,sessionSectorId,rowName,number',
                'sessionSeat.sessionSector:id,sessionAreaId,name',
                'sessionSeat.sessionSector.sessionArea:id,name',
                'client:id,userId',
                'client.user:id,name,surname,nif',
            ],
            'defaultOrderBy' => 'Access.createdAt',
            'defaultSortOrder' => 'desc',
        ]);
        $this->configureDataGrid([
            'modelClass' => Access::class,
            'dataGridTitle' => 'Accesos',
            'defaultOrderBy' => 'Access.createdAt',
            'defaultSortOrder' => 'desc',
        ]);
    }

    /**
     * Registers (try) an access.
     *
     * @param Request $request
     * @param Session $session
     * @param SessionDoor $sessionDoor
     * @param $locator
     * @return JsonResponse
     */
    public function register(Request $request, Session $session, SessionDoor $sessionDoor, $locator): JsonResponse
    {
        $forceRegister = $request->get('forceRegister');
        $ticket = Ticket::whereLocator($locator)->with('order')->first();
        $sessionDoorCheck = $ticket->sessionSeat->sessionSector->sessionDoors()
            ->where('SessionDoor.id', '=', $sessionDoor->id)
            ->exists();

        if (!$sessionDoorCheck && !$forceRegister) {
            return response()->json([
                'accessSuccess' => false,
                'doorCheck' => false,
                'strictDoorAccess' => $session->strictDoorAccess
            ]);
        }

        // Error management if the ticket exists
        if ($ticket) {
            $access = new Access();
            $access->session()->associate($session);
            $access->ticket()->associate($ticket);
            $access->sessionSeat()->associate($ticket->sessionSeatId);
            $access->client()->associate($ticket->order->clientId);
            // Different session ERROR
            if ($ticket->sessionId !== $session->id) {
                $access->status = 'error';
                $access->save();

                $access->loadMissing([
                    'sessionSeat:id,sessionSectorId,rowName,number',
                    'sessionSeat.sessionSector:id,sessionAreaId,name',
                    'sessionSeat.sessionSector.sessionArea:id,name',
                    'client:id,name,surname,nif',
                ]);

                return response()->json(['error' => 'Evento incorrecto', 'access' => $access], 400);
            }
            // Already in ERROR
            $existingAccess = Access::whereSessionId($session->id)
                ->whereTicketId($ticket->id)
                ->whereIn('status', ['successful', 'out'])
                ->orderBy('createdAt', 'desc')
                ->first();
            if ($existingAccess && $existingAccess->status === 'successful') {
                $access->status = 'error';
                $access->save();

                $access->loadMissing([
                    'sessionSeat:id,sessionSectorId,rowName,number',
                    'sessionSeat.sessionSector:id,sessionAreaId,name',
                    'sessionSeat.sessionSector.sessionArea:id,name',
                    'client:id,name,surname,nif',
                ]);

                return response()->json(['error' => 'Cliente repetido', 'access' => $access], 400);
            }
            // Register the access
            $access->status = 'successful';
            $access->save();
            return response()->json(['accessSuccess' => true]);
        }
        return response()->json(['error' => 'Ha ocurrido un error al registrar el acceso'], 400);
    }

    /**
     * Registers an out.
     *
     * @param Request $request
     * @param Session $session
     * @param SessionDoor $sessionDoor
     * @param $locator
     * @return JsonResponse
     */
    public function registerOut(Request $request, Session $session, SessionDoor $sessionDoor, $locator): JsonResponse
    {
        $forceRegisterOut = $request->get('forceRegisterOut');
        $ticket = Ticket::whereLocator($locator)->with('order')->first();
        $sessionDoorCheck = $ticket->sessionSeat->sessionSector->sessionDoors()
            ->where('SessionDoor.id', '=', $sessionDoor->id)
            ->exists();

        if (!$sessionDoorCheck && !$forceRegisterOut) {
            return response()->json([
                'outSuccess' => false,
                'doorCheck' => false,
                'strictDoorAccess' => $session->strictDoorAccess
            ]);
        }

        // Error management if the ticket exists
        if ($ticket) {
            $access = new Access();
            $access->session()->associate($session);
            $access->ticket()->associate($ticket);
            $access->sessionSeat()->associate($ticket->sessionSeatId);
            $access->client()->associate($ticket->order->clientId);
            // Different session ERROR
            if ($ticket->sessionId !== $session->id) {
                $access->status = 'error';
                $access->save();
                return response()->json(['error' => 'Evento incorrecto'], 400);
            }
            // Already out ERROR
            $existingOut = Access::whereSessionId($session->id)
                ->whereTicketId($ticket->id)
                ->whereIn('status', ['successful', 'out'])
                ->orderBy('createdAt', 'desc')
                ->first();
            if ($existingOut && $existingOut->status === 'out') {
                $access->status = 'error';
                $access->save();
                return response()->json(['error' => 'El cliente ya ha salido del recinto'], 400);
            }
            // Not in ERROR
            $existingAccess = Access::whereSessionId($session->id)
                ->whereTicketId($ticket->id)
                ->whereStatus('successful')
                ->orderBy('createdAt', 'desc')
                ->first();
            if (!$existingAccess) {
                $access->status = 'error';
                $access->save();
                return response()->json(['error' => 'El cliente no ha entrado en el recinto'], 400);
            }
            // Register the out
            $access->status = 'out';
            $access->save();
            return response()->json(['outSuccess' => true]);
        }
        return response()->json(['error' => 'Ha ocurrido un error al registrar la salida'], 400);
    }

    /**
     * @param Session $session
     * @return int
     */
    public function successfulAccessesCheckCount(Session $session): int
    {
        return $session->successfulAccessesCheckCount();
    }
}
