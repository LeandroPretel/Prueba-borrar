<?php

namespace App\Http\Controllers;

use App\Fare;
use App\TicketSeason;
use App\TicketSeasonPrintModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Files\Controllers\FileController;
use Savitar\Models\SavitarModel;

class TicketSeasonController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * TicketSeasonController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(TicketSeason::class);
        $this->initCustomAuthorization(TicketSeason::class, [
            'printModel' => 'view',
        ]);
        $this->configureCRUD([
            'modelClass' => TicketSeason::class,
            'indexAppends' => [
            ],
            'showAppends' => [
                'place.province',
                'space',
                'sessions.showTemplate',
                'sessions.sessionAreas',
                'sessions.sessionAreas.fares',
                'sessions.sessionAreas.sessionSectors',
                'pointsOfSale',
                'files',
                'fares.sessionAreas',
                'fares.pointsOfSale'
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => TicketSeason::class,
            'dataGridTitle' => 'Abonos',
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

    /**
     * @param Request $request
     * @param TicketSeason $ticketSeason
     * @return JsonResponse
     */
    public function printModel(Request $request, TicketSeason $ticketSeason): JsonResponse
    {
        $ticketSeasonPrintModel = $ticketSeason->ticketSeasonPrintModel()->with('files')->first();
        return response()->json([
            'sessionPrintModel' => $ticketSeasonPrintModel,
        ], 200);
    }

    /**
     * @param Request $request
     * @param TicketSeason $ticketSeason
     * @return JsonResponse
     */
    public function updatePrintModel(Request $request, TicketSeason $ticketSeason): JsonResponse
    {
        $printModel = $ticketSeason->ticketSeasonPrintModel;
        if (!$printModel) {
            $printModel = new TicketSeasonPrintModel();
            $printModel->ticketSeason()->associate($printModel);
        }
        $printModel->font = $request->input('font');
        $printModel->save();
        // Save files
        if ($request->filled('mainImage')) {
            FileController::associateFilesToModel($printModel, [$request->input('mainImage')]);
        }

        return response()->json([
            'sessionPrintModel' => $printModel
        ], 200);
    }

    protected function savedHook(Request $request, TicketSeason $ticketSeason, string $saveOrUpdate): void
    {
        // Fares
        if ($request->has('fares')) {
            foreach ($request->input('fares') as $fareRequest) {
                if ($fareRequest['id']) {
                    $fare = Fare::find($fareRequest['id']);
                } else {
                    $fare = new Fare();
                    $fare->session()->associate($fareRequest['sessionId']);
                    $fare->ticketSeason()->associate($ticketSeason);
                }

                $fare->name = $fareRequest['name'];
                $fare->webName = $fareRequest['webName'];
                $fare->ticketName = $fareRequest['ticketName'];
                $fare->minTicketsByOrder = $fareRequest['minTicketsByOrder'];
                $fare->maxTicketsByOrder = $fareRequest['maxTicketsByOrder'];
                $fare->maxTickets = $fareRequest['maxTickets'];
                $fare->description = $fareRequest['description'];
                $fare->webDescription = $fareRequest['webDescription'];
                $fare->assistedPointOfSaleMessage = $fareRequest['assistedPointOfSaleMessage'];
                $fare->checkIdentity = isset($fareRequest['checkIdentity']) ? $fareRequest['checkIdentity'] : false;
                $fare->checkIdentityMessage = $fareRequest['checkIdentityMessage'];
                $fare->associatedToTuPalacio = isset($fareRequest['associatedToTuPalacio']) ? $fareRequest['associatedToTuPalacio'] : false;
                $fare->isPromotion = isset($fareRequest['isPromotion']) ? $fareRequest['isPromotion'] : false;
                $fare->isSeason = isset($fareRequest['isSeason']) ? $fareRequest['isSeason'] : false;
                $fare->observations = $fareRequest['observations'];

                // Fare restriction dates
                if ($fareRequest['restrictionStartDate']) {
                    $fare->restrictionStartDate = Carbon::createFromTimestamp(strtotime($fareRequest['restrictionStartDate']));
                    if ($fareRequest['restrictionStartDateTime']) {
                        $fare->restrictionStartDate = $fare->restrictionStartDate->setTimeFromTimeString($fareRequest['restrictionStartDateTime']);
                    }
                } else {
                    $fare->restrictionStartDate = null;
                }
                if ($fareRequest['restrictionEndDate']) {
                    $fare->restrictionEndDate = Carbon::createFromTimestamp(strtotime($fareRequest['restrictionEndDate']));
                    if ($fareRequest['restrictionEndDateTime']) {
                        $fare->restrictionEndDate = $fare->restrictionEndDate->setTimeFromTimeString($fareRequest['restrictionEndDateTime']);
                    }
                } else {
                    $fare->restrictionEndDate = null;
                }

                $fare->save();

                // Areas
                if (isset($fareRequest['sessionAreas'])) {
                    $arrayToSync = [];
                    foreach ($fareRequest['sessionAreas'] as $sessionAreaRequest) {
                        $arrayToSync[$sessionAreaRequest['sessionAreaId']] = [
                            'isActive' => $sessionAreaRequest['isActive'],
                            'earlyPrice' => $sessionAreaRequest['earlyPrice'],
                            'earlyDistributionPrice' => $sessionAreaRequest['earlyDistributionPrice'],
                            'earlyTotalPrice' => $sessionAreaRequest['earlyPrice'] + $sessionAreaRequest['earlyDistributionPrice'],
                            'ticketOfficePrice' => $sessionAreaRequest['ticketOfficePrice'],
                            'ticketOfficeDistributionPrice' => $sessionAreaRequest['ticketOfficeDistributionPrice'],
                            'ticketOfficeTotalPrice' => $sessionAreaRequest['ticketOfficePrice'] + $sessionAreaRequest['ticketOfficeDistributionPrice'],
                        ];
                    }
                    $fare->sessionAreas()->sync($arrayToSync);
                }

                if (isset($fareRequest['pointsOfSale'])) {
                    // PointsOfSale
                    $arrayToSync = [];
                    foreach ($fareRequest['pointsOfSale'] as $pointOfSaleRequest) {
                        $arrayToSync[$pointOfSaleRequest['pointOfSaleId']] = [
                            'maximumTicketsToSell' => $pointOfSaleRequest['maximumTicketsToSell'],
                        ];
                    }
                    $fare->pointsOfSale()->sync($arrayToSync);
                }
            }
        }
    }
}
