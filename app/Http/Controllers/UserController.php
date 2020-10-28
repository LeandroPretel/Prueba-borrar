<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Savitar\Auth\Mail\ConfirmMail;
use Savitar\Auth\Mail\NewPasswordMail;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarUser;
use Savitar\Auth\Traits\Authorization;
use Savitar\DataGrid\Traits\DataGrid;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    use DataGrid;
    use Authorization;

    private $usersDataGridHeaders;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(User::class);

        $this->usersDataGridHeaders = [
            'profileImageUrl' => [
                'name' => 'Imagen',
                'type' => 'icon',
                'sql' => 'profileImageUrl',
                'notSortable' => true,
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'User.name',
                'default' => true,
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'User.email',
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'User.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Bloqueado', 'translation' => 'Bloqueado', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                ],
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'emailConfirmed' => [
                'name' => 'Estado email',
                'type' => 'boolean',
                'sql' => 'User.emailConfirmed',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No verificado', 'translation' => 'No verificado', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Verificado', 'translation' => 'Verificado', 'statusColor' => 'green-status'],
                ],
                'update' => false,
                'save' => false,
            ],
            'roleName' => [
                'name' => 'Rol',
                'type' => 'string',
                'sql' => 'Role.name',
                'foreignKey' => 'User.roleId',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'User.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'User.createdAt',
                'default' => true,
                'profile' => false,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'User.createdBy',
                'profile' => false,
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'User.updatedAt',
                'profile' => false,
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'User.updatedBy',
                'profile' => false,
            ],
        ];
        $this->configureDataGrid([
            'modelClass' => User::class,
            'dataGridHeaders' => $this->usersDataGridHeaders,
            'defaultOrderBy' => 'email',
            'dataGridTitle' => 'Usuarios',
        ]);
    }

    /**
     * Admins dataGrid.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws JWTException
     */
    public function adminsDataGrid(Request $request)
    {
        $this->setDataGridConditions([
            ['column' => 'Role.slug', 'operator' => '<>', 'value' => 'punto-de-venta'],
            ['column' => 'Role.slug', 'operator' => '<>', 'value' => 'cliente'],
            ['column' => 'Role.slug', 'operator' => '<>', 'value' => 'control-de-accesos'],
            ['column' => 'Role.slug', 'operator' => '<>', 'value' => 'super-admin'],
            ['column' => 'Role.slug', 'operator' => '<>', 'value' => 'promotor'],
            ['column' => 'Role.slug', 'operator' => '<>', 'value' => 'promotor-descuentos'],
        ]);
        return $this->dataGrid($request);
    }

    /**
     * DataGrid filtered with only access control users.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws JWTException
     */
    public function accessUsersDataGrid(Request $request)
    {
        $headers = $this->usersDataGridHeaders;
        $headers['roleName']['default'] = false;
        unset($headers['profileImageUrl']);
        $profileImageUrlAttribute = [
            'name' => 'Imagen',
            'type' => 'icon',
            'sql' => 'profileImageUrl',
            'notSortable' => true,
            'default' => true,
            'update' => false,
            'save' => false,
        ];
        $accountNameAttribute = [
            'name' => 'Cuenta',
            'type' => 'string',
            'sql' => 'Account.name',
            'foreignKey' => 'User.accountId',
            'update' => false,
            'save' => false,
            'default' => true,
        ];
        $headers = array('profileImageUrl' => $profileImageUrlAttribute) + array('accountName' => $accountNameAttribute) + $headers;

        $this->setDataGridHeaders($headers);
        $this->setDataGridConditions([
            ['column' => 'Role.slug', 'operator' => '=', 'value' => 'control-de-accesos'],
        ]);
        return $this->dataGrid($request);
    }

    /**
     * Stores a new Access Control user.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function storeAccessUser(Request $request)
    {
        $user = new SavitarUser();
        if ($request->filled('accountId')) {
            $user->account()->associate($request->input('accountId'));
        }
        $user->role()->associate(SavitarRole::where('slug', 'control-de-accesos')->first());
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response(200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function registerClient(Request $request): JsonResponse
    {
        $role = SavitarRole::whereSlug('cliente')->first();
        if (isset($role)) {
            $user = new User();
            $user->role()->associate($role);
            $user->email = $request->input('email');
            $user->name = $request->input('name');
            if ($request->has('matchingPassword.password')) {
                $passwordLength = strlen($request->input('matchingPassword.password'));
                if ($passwordLength < 6 || $passwordLength > 30) {
                    abort(500, 'Wrong password size.');
                }
                $user->password = bcrypt($request->input('matchingPassword.password'));
            }
            $user->save();

            $client = new Client();
            $client->user()->associate($user);
            $client->name = $request->input('name');
            $client->surname = $request->input('surname');
            $client->nif = $request->input('nif');
            $client->phone = $request->input('phone');
            $client->save();

            $token = base64_encode(Carbon::now()->toIso8601String() . '.' . $user->id) . '.'
                . base64_encode(hash_hmac('sha256', Carbon::now()->toIso8601String() . '.' . $user->id, config('savitar_auth.app_key')));

            $mailRoutes = config('savitar_auth.confirm_mail_routes', []);
            // If the user has no password.
            if ($mailRoutes && isset($mailRoutes[$user->role->slug])) {
                $url = $mailRoutes[$user->role->slug]['withoutPassword'] . urlencode($token);
            } else {
                $url = config('savitar_auth.app_url') . '/confirmacion-credenciales/' . urlencode($token);
            }
            $title = 'Bienvenido a Redentradas';
            $subtitle = 'Establece tu contraseña';
            $content = 'Se te ha dado de alta en Redentradas, por favor, establece tu contraseña utilizando el siguiente botón.';
            // Try to send the email and catch the error.
            try {
                Mail::to($user->email)->send(
                    new ConfirmMail(
                        $user->email,
                        $url, $title,
                        $subtitle,
                        [$content],
                        config('savitar_auth.confirm_mail_logo_url'),
                        config('savitar_auth.app_theme', '#e85d0f'),
                        config('savitar_auth.confirm_mail_help_email'),
                        config('savitar_auth.confirm_mail_help_web')
                    ));
                Log::info('Se ha enviado un correo de confirmación de email a ' . $user->email);
            } catch (Exception $exception) {
                Log::error('-----' . config('savitar_auth.confirm_mail_help_web'));
                Log::error('Ha ocurrido un error al enviar el correo de confirmación a' . $user->email . '. ' . $exception->getMessage());
            }
            return response()->json(['userId' => $user->id, 'clientId' => $client->id, 'human-readable-reference' => $user->email], 200);
        }
        return response()->json(['error' => 'Ha ocurrido un error al seleccionar el rol'], 401);
    }
}
