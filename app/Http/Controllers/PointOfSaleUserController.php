<?php

namespace App\Http\Controllers;

use App\PointOfSaleUser;
use App\User;
use Illuminate\Http\Request;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarUser;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Files\Controllers\FileController;

class PointOfSaleUserController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * PointOfSaleUserController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(PointOfSaleUser::class);
        $this->configureCRUD([
            'modelClass' => PointOfSaleUser::class,
            'showAppends' => ['user.files'],
            'indexJoins' => ['User' => 'userId'],
            'indexAttributes' => ['User.name', 'User.email'],
            'defaultOrderBy' => 'User.email',
        ]);
        $this->configureDataGrid([
            'modelClass' => PointOfSaleUser::class,
            'defaultOrderBy' => 'email',
            'dataGridTitle' => 'Usuarios de punto de venta',
        ]);
    }

    /**
     * @param Request $request
     * @param PointOfSaleUser $pointOfSaleUser
     * @param $saveOrUpdate
     */
    protected function savingHook(Request $request, PointOfSaleUser $pointOfSaleUser, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            if (!$pointOfSaleUser->userId) {
                $user = new User();
            } else {
                $user = $pointOfSaleUser->user;
            }
            $user->role()->associate(SavitarRole::whereSlug('punto-de-venta')->first());

            if ($request->has('name')) {
                $user->name = $request->input('name');
            }
            if ($request->has('email')) {
                $user->email = $request->input('email');
            }
            if ($request->has('password')) {
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

            $pointOfSaleUser->user()->associate($user);
        }
    }

    /**
     * @param Request $request
     * @param PointOfSaleUser $pointOfSaleUser
     * @param $saveOrUpdate
     */
    protected function savedHook(Request $request, PointOfSaleUser $pointOfSaleUser, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            $savitarUser = SavitarUser::whereId($pointOfSaleUser->userId)->first();
            FileController::associateFilesToModel($savitarUser,
                [$request->input('profileImage')]
            );
        }
    }
}
