<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

/**
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Account::class);
        $this->configureCRUD([
            'modelClass' => Account::class,
            'showAppends' => [
                'users.role',
                'files',
                'province',
                'country',
                'billingData',
                'pointsOfSale',
                'enterprises',
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => Account::class,
            'dataGridConditions' => [
                ['column' => 'isSuperAdminAccount', 'operator' => '=', 'value' => false],
            ],
            'dataGridTitle' => 'Promotores',
        ]);
    }

    /**
     * @param Request $request
     * @param Account $account
     * @param $saveOrUpdate
     */
    public function savedHook(Request $request, Account $account, $saveOrUpdate): void
    {
        if ($saveOrUpdate && $request->filled('enterpriseId')) {
            $account->enterprises()->sync($request->input('enterpriseId'));
        }
    }
}
