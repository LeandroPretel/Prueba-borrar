<?php

namespace App\Http\Controllers;

use App\Fare;
use App\PointOfSale;
use App\SessionAreaFare;
use App\Ticket;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FareController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * FareController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Fare::class);
        $this->configureCRUD([
            'modelClass' => Fare::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Fare::class,
            'dataGridTitle' => 'Tarifas',
        ]);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param Fare $fare
     * @return JsonResponse
     */
    public function ticketCountByFarePointOfSale(Request $request, PointOfSale $pointOfSale, Fare $fare): JsonResponse
    {
        $count = Ticket::join('SessionAreaFare', 'Ticket.sessionAreaFareId', '=','SessionAreaFare.id')
            ->join('Fare', 'Fare.id', '=', 'SessionAreaFare.fareId')
            ->join('FarePointOfSale', 'Fare.id', '=', 'FarePointOfSale.fareId')
            ->where('Fare.id', '=', $fare->id)
            ->where('FarePointOfSale.pointOfSaleId', '=', $pointOfSale->id)
            ->count();

        $farePointOfSale = $fare->pointsOfSale()->find($pointOfSale->id);

        return response()->json([
            'ticketCount' => $count,
            'pointOfSale' => $farePointOfSale
        ]);
    }
}
