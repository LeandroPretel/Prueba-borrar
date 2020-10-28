<?php

namespace App\Http\Controllers;

use App\Client;
use App\SessionSeat;
use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarUser;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Files\Controllers\FileController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ClientController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * ClientController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Client::class);
        $this->initCustomAuthorization(Client::class, [
            'activeDataGrid' => 'view',
        ]);
        $this->configureCRUD([
            'modelClass' => Client::class,
            'showAppends' => ['user.files', 'province', 'country'],
            'indexJoins' => ['User' => 'userId'],
            'indexAttributes' => ['Client.name', 'Client.surname', 'User.email'],
            'defaultOrderBy' => 'Client.surname',
        ]);
        $this->configureDataGrid([
            'modelClass' => Client::class,
            'defaultOrderBy' => 'Client.surname',
            'dataGridTitle' => 'Clientes',
        ]);
    }

    /**
     * Profile
     *
     * Retrieves the authenticated client information.
     *
     * @param Client $client
     * @return Builder|SavitarUser|object|JsonResponse|null
     * @throws JWTException
     */
    public function profile(Client $client)
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $connection = config('savitar_auth.db_connection');
        if ($connection === 'pgsql') {
            $savitarUser = SavitarUser::where('email', 'ILIKE', $payload['email'])->first();
        } else {
            $savitarUser = SavitarUser::where('email', 'like', $payload['email'])->first();
        }
        if ($savitarUser && $client->userId === $savitarUser->id) {
            $client->loadMissing(['user.files']);
            return $client;
        }
        return null;
    }

    /**
     * Update profile
     *
     * Updates the authenticated client.
     *
     * @response {
     *  "id": "42aee1ec-326e-4350-afb3-28b3f747841d",
     *  "humanReadableReference": "Sergio"
     * }
     *
     * @param Request $request
     * @param Client $client
     * @return JsonResponse|null
     * @throws JWTException
     */
    public function updateProfile(Request $request, Client $client): ?JsonResponse
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $connection = config('savitar_auth.db_connection');
        if ($connection === 'pgsql') {
            $savitarUser = SavitarUser::where('email', 'ILIKE', $payload['email'])->first();
        } else {
            $savitarUser = SavitarUser::where('email', 'like', $payload['email'])->first();
        }

        if ($savitarUser && $client->userId === $savitarUser->id) {
            $this->applyRequestToModel($request, $client, 'update');
            return response()->json(['id' => $client['id'], 'humanReadableReference' => $client->getAttribute($this->defaultOrderBy)]);
        }
        return null;
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response|JsonResponse
     */
    public function clientRegister(Request $request)
    {
        $existingNifClient = Client::where('nif', '=', $request->input('nif'))->count();
        if ($existingNifClient) {
            return response()->json(['error' => 'El nif ya está en uso'], 403);
        }

        $user = new SavitarUser();
        $user->role()->associate(SavitarRole::where('slug', 'cliente')->first());
        $user->email = $request->input('email');
        $user->canReceiveEmails = $request->input('canReceiveEmails');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        $client = new Client();
        $client->user()->associate($user);
        $client->name = $request->input('name');
        $client->surname = $request->input('surname');
        $client->nif = $request->input('nif');
        $client->phone = $request->input('phone');
        $client->save();

        // assign client to reserved seats
        $sessionSeatsIds = $request->get('selectedSeats');
        if ($sessionSeatsIds) {
            SessionSeat::whereIn('id', $sessionSeatsIds)
                ->where('status', '=', 'reserved')
                ->update([
                    'updatedBy' => $user->email
                ]);
        }

        return response(200);
    }

    /**
     * @param Request $request
     * @param Client $client
     * @param $saveOrUpdate
     */
    protected function savingHook(Request $request, Client $client, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            if (!$client->userId) {
                $user = new User();
            } else {
                $user = $client->user;
            }
            $user->role()->associate(SavitarRole::whereSlug('cliente')->first());

            if ($request->has('email')) {
                $user->email = $request->input('email');
            }
            if ($request->filled('password')) {
                $user->password = bcrypt($request->input('password'));
            } elseif ($request->filled('matchingPassword.password')) {
                $password_length = strlen($request->input('matchingPassword.password'));
                if ($password_length < 6 || $password_length > 20) {
                    abort(500, 'Wrong password size.');
                }
                $user->password = bcrypt($request->input('matchingPassword.password'));
            }
            if ($request->has('canReceiveNotifications')) {
                $user->canReceiveNotifications = $request->input('canReceiveNotifications');
            }
            if ($request->has('canReceiveEmails')) {
                $user->canReceiveEmails = $request->input('canReceiveEmails');
            }
            if ($request->has('observations')) {
                $user->observations = $request->input('observations');
            }
            $user->save();

            $client->user()->associate($user);
        }
    }

    /**
     * @param Request $request
     * @param Client $client
     * @param $saveOrUpdate
     */
    protected function savedHook(Request $request, Client $client, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            $savitarUser = SavitarUser::whereId($client->userId)->first();
            FileController::associateFilesToModel($savitarUser,
                [$request->input('profileImage')]
            );
        }
    }

    /**
     * @param Request $request
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function activeDataGrid(Request $request)
    {
        $this->setDataGridConditions([['column' => 'isActive', 'operator' => '=', 'value' => true]]);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param Client $client
     * @return ResponseFactory|JsonResponse|Response
     * @throws Exception
     */
    public function applyForDelete(Request $request, Client $client)
    {
        $userEmail = $this->getCurrentUser();
        $userToDelete = $client->user;
        if ($userEmail === 'SuperAdmin' || $userEmail === $userToDelete->email) {
            return $this->destroy($request);
        }
        return response(401);
    }

    /**
     * Check NIF
     *
     * Checks if an existing client have the nif.
     *
     * @urlParam nif string required The nif to check. Example: 87428906H
     * @response {
     *  "check": true,
     *  "client": "[Client]"
     * }
     *
     * @param string $nif
     * @return JsonResponse
     */
    public function checkNif(string $nif): JsonResponse
    {
        $client = Client::where('nif', $nif)->first();
        $client ? $check = true : $check = false;

        return response()->json(['check' => $check, 'client' => $client]);
    }
}
