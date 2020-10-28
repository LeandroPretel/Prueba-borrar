<?php

namespace App\Http\Controllers;

use App\Place;
use App\PlacePointOfSale;
use App\PointOfSale;
use App\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class PointOfSaleController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * PointOfSaleController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(PointOfSale::class);
        $this->configureCRUD([
            'modelClass' => PointOfSale::class,
            'indexAppends' => [
                'province',
            ],
            'showAppends' => [
                'province',
                'sessions',
                'accounts',
                'places',
                'enterprises',
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => PointOfSale::class,
            'dataGridTitle' => 'Puntos de venta',
        ]);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param $saveOrUpdate
     */
    public function savedHook(Request $request, PointOfSale $pointOfSale, $saveOrUpdate): void
    {
        if ($saveOrUpdate && $request->filled('enterpriseId')) {
            $pointOfSale->enterprises()->sync($request->input('enterpriseId'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function publicIndex(Request $request): JsonResponse
    {
        $pointsOfSale = PointOfSale::select([
            'id', 'isWeb', 'isAutomatic', 'isAssisted', 'isByPhone', 'name', 'city', 'address', 'provinceId', 'openingHours', 'serviceServedEnabled',
            'ticketPickUpEnabled', 'ticketSalesEnabled', 'cashEnabled', 'creditCardEnabled',
        ])->where('isWeb', '=', false)
            ->where('isByPhone', '=', false)
            ->where('isActive', '=', true)
            ->where('isVisible', '=', true)
            ->with(['province'])
            ->get();

        return response()->json(['pointsOfSale' => $pointsOfSale], 200);
    }

    /**
     * Returns the index of the points of sale candidates to be a ticketOffice
     * @param Request $request
     * @return LengthAwarePaginator|Builder[]|JsonResponse|Collection
     */
    public function ticketOfficeIndex(Request $request)
    {
        $this->setIndexConditions([
            ['column' => 'isWeb', 'operator' => '=', 'value' => false],
        ]);
        return $this->index($request);
    }

    /**
     * @param PointOfSale $pointOfSale
     * @param Place $place
     * @return JsonResponse
     */
    public function checkIfTicketOffice(PointOfSale $pointOfSale, Place $place): JsonResponse
    {
        $ticketOffice = PlacePointOfSale::where('placeId', $place->id)
            ->where('pointOfSaleId', $pointOfSale->id)
            ->first();

        $ticketOffice ? $check = true : $check = false;
        return response()->json(['check' => $check], 200);
    }

    /**
     * @param PointOfSale $pointOfSale
     * @param Place $place
     * @param Session $session
     * @return JsonResponse
     */
    public function checkIfTicketOfficeForSession(PointOfSale $pointOfSale, Place $place, Session $session): JsonResponse
    {
        $ticketOffice = PlacePointOfSale::where('placeId', $place->id)
            ->where('pointOfSaleId', $pointOfSale->id)
            ->first();

        $check = false;
        if ($ticketOffice && $session->ticketOfficesEnabled && $session->ticketOfficesStartDate && $session->ticketOfficesEndDate) {
            $check = Carbon::now()->between($session->ticketOfficesStartDate, $session->ticketOfficesEndDate);
        }
        return response()->json(['check' => $check], 200);
    }
}
