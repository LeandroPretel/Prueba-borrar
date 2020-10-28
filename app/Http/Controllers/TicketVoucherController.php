<?php

namespace App\Http\Controllers;

use App\TicketVoucher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Models\SavitarModel;

class TicketVoucherController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * TicketVoucherController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(TicketVoucher::class);
        $this->configureCRUD([
            'modelClass' => TicketVoucher::class,
            'indexAppends' => [
            ],
            'showAppends' => [
                'sessions.showTemplate',
                'sessions.place.province',
                'pointsOfSale',
                'files'
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => TicketVoucher::class,
            'dataGridTitle' => 'Bonos',
        ]);
    }

    /**
     * @param Request $request
     * @return Builder|Builder[]|Model|Collection|mixed|SavitarModel|SavitarModel[]
     */
    public function publicShow(Request $request)
    {
        return $this->show($request);
    }
}
