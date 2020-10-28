<?php

namespace App\Http\Controllers;

use App\Account;
use App\Fare;
use App\PointOfSale;
use App\Session;
use App\SessionArea;
use App\SessionAreaFare;
use App\SessionPrintModel;
use App\SessionSeat;
use App\SessionSector;
use App\Show;
use App\Space;
use App\Ticket;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Files\Controllers\FileController;
use Savitar\Files\SavitarFile;
use Savitar\Models\SavitarModel;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class SessionController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Session::class);
        $this->initCustomAuthorization(Session::class, [
            'printModel' => 'view',
        ]);
        $this->configureCRUD([
            'modelClass' => Session::class,
            'indexConditions' => [
                ['column' => 'status', 'operator' => '<>', 'value' => 'Agotada'],
                ['column' => 'status', 'operator' => '<>', 'value' => 'Finalizada'],
            ],
            'indexJoins' => ['ShowTemplate' => 'showTemplateId'],
            // , 'Session.date'
            'indexAttributes' => ['ShowTemplate.name'],
            'showAppends' => [
                'showTemplate',
                'sessionAreas',
                'show.account',
                'place.province',
                // 'space.sessionAreas.sessionSectors',
                'fares.sessionAreas',
                'fares.pointsOfSale',
                'pointsOfSale',
                // 'sessionSeats',
                'externalEnterprise',
                'partners',
                // 'sessionSeats'
            ],
//            'showCountAppends' => [
//                'availableSessionSeats',
//                'soldSessionSeats',
//                'isForDisabledSessionSeats',
//                'hardTicketSessionSeats',
//            ],
            'defaultOrderBy' => 'date',
        ]);
        $this->configureDataGrid([
            'modelClass' => Session::class,
            'dataGridTitle' => 'Sesiones',
            'defaultOrderBy' => 'date',
        ]);
    }

    /**
     * DataGrid with custom session filters.
     *
     * @param Request $request
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    protected function customDataGrid(Request $request)
    {
        $sessionsOptionalFilter = [];
        if ($request->query('showHiddenSessions') === 'true') {
            $this->setDataGridConditions([['column' => 'Session.isHidden', 'operator' => '=', 'value' => true]]);
        }
        if ($request->query('showNextSessions') === 'true') {
            $sessionsOptionalFilter[] = ['column' => 'Session.displayAsSoon', 'operator' => '=', 'value' => true];
        }
        if ($request->query('showOnSaleSessions') === 'true') {
            $sessionsOptionalFilter[] = ['column' => 'Session.status', 'operator' => '=', 'value' => 'A la venta'];
        }
        if ($request->query('showOutOfTicketsSessions') === 'true') {
            $sessionsOptionalFilter[] = ['column' => 'Session.status', 'operator' => '=', 'value' => 'Agotada'];
        }
        if ($request->query('showFinishedSessions') === 'true') {
            $sessionsOptionalFilter[] = ['column' => 'Session.status', 'operator' => '=', 'value' => 'Finalizada'];
        }
        if (count($sessionsOptionalFilter) > 0) {
            $this->setDataGridOptionalConditions($sessionsOptionalFilter);
        }
        return $this->dataGrid($request);
    }

    /**
     * Retrieve
     *
     * Retrieves the details of an existing resource from the database.
     *
     * @param Request $request
     * @return Builder|Builder[]|Collection|Model|mixed|SavitarModel|SavitarModel[]
     */
    public function show(Request $request)
    {
        /** @var Session $model */
        $model = $this->getModelFromRoute($request, false, true);
        if ($this->showAppends) {
            $model->loadMissing($this->showAppends);
            $space = $model->space()->with([
                'sessionAreas' => static function ($query) use ($model) {
                    $query->where('sessionId', $model->id)
                        ->with('sessionSectors');
                },
            ])->first();
            $model['space'] = $space;
        }
        return $model;
    }

    /**
     * @param Request $request
     * @param Session $session
     * @param $saveOrUpdate
     */
    protected function savingHook(Request $request, Session $session, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            // Main session date
            $session->date = Carbon::createFromTimestamp(strtotime($request->input('date')))->setTimeFromTimeString($request->input('time'));
            // Advance sale
            if ($request->has('advanceSaleStartDate') && $request->has('advanceSaleStartTime')) {
                if ($request->input('advanceSaleStartDate') && $request->input('advanceSaleStartTime')) {
                    $session->advanceSaleStartDate = Carbon::createFromTimestamp(strtotime($request->input('advanceSaleStartDate')))->setTimeFromTimeString($request->input('advanceSaleStartTime'));
                } else {
                    $session->advanceSaleStartDate = null;
                }
            }
            if ($request->has('advanceSaleFinishDate') && $request->has('advanceSaleFinishTime')) {
                if ($request->input('advanceSaleFinishDate') && $request->input('advanceSaleFinishTime')) {
                    $session->advanceSaleFinishDate = Carbon::createFromTimestamp(strtotime($request->input('advanceSaleFinishDate')))->setTimeFromTimeString($request->input('advanceSaleFinishTime'));
                } else {
                    $session->advanceSaleFinishDate = null;
                }
            }
            // Assisted close
            if ($request->has('assistedSellEndDate') && $request->has('assistedSellEndTime')) {
                if ($request->input('assistedSellEndDate') && $request->input('assistedSellEndTime')) {
                    $session->assistedSellEndDate = Carbon::createFromTimestamp(strtotime($request->input('assistedSellEndDate')))->setTimeFromTimeString($request->input('assistedSellEndTime'));
                } else {
                    $session->assistedSellEndDate = null;
                }
            }
            // Pick up dates in ticketOffices
            if ($request->has('ticketOfficesStartDate') && $request->has('ticketOfficesStartTime')) {
                if ($request->input('ticketOfficesStartDate') && $request->input('ticketOfficesStartTime')) {
                    $session->ticketOfficesStartDate = Carbon::createFromTimestamp(strtotime($request->input('ticketOfficesStartDate')))->setTimeFromTimeString($request->input('ticketOfficesStartTime'));
                } else {
                    $session->ticketOfficesStartDate = null;
                }
            }
            if ($request->has('ticketOfficesEndDate') && $request->has('ticketOfficesEndTime')) {
                if ($request->input('ticketOfficesEndDate') && $request->input('ticketOfficesEndTime')) {
                    $session->ticketOfficesEndDate = Carbon::createFromTimestamp(strtotime($request->input('ticketOfficesEndDate')))->setTimeFromTimeString($request->input('ticketOfficesEndTime'));
                } else {
                    $session->ticketOfficesEndDate = null;
                }
            }
            // Pick up dates in pointOfSale
            if ($request->has('pickUpInPointsOfSaleStartDate') && $request->has('pickUpInPointsOfSaleStartTime')) {
                if ($request->input('pickUpInPointsOfSaleStartDate') && $request->input('pickUpInPointsOfSaleStartTime')) {
                    $session->pickUpInPointsOfSaleStartDate = Carbon::createFromTimestamp(strtotime($request->input('pickUpInPointsOfSaleStartDate')))->setTimeFromTimeString($request->input('pickUpInPointsOfSaleStartTime'));
                } else {
                    $session->pickUpInPointsOfSaleStartDate = null;
                }
            }
            if ($request->has('pickUpInPointsOfSaleEndDate') && $request->has('pickUpInPointsOfSaleEndTime')) {
                if ($request->input('pickUpInPointsOfSaleEndDate') && $request->input('pickUpInPointsOfSaleEndTime')) {
                    $session->pickUpInPointsOfSaleEndDate = Carbon::createFromTimestamp(strtotime($request->input('pickUpInPointsOfSaleEndDate')))->setTimeFromTimeString($request->input('pickUpInPointsOfSaleEndTime'));
                } else {
                    $session->pickUpInPointsOfSaleEndDate = null;
                }
            }
            // Access control
            if ($request->has('openingDoorsDate') && $request->has('openingDoorsTime')) {
                if ($request->input('openingDoorsDate') && $request->input('openingDoorsTime')) {
                    $session->openingDoorsDate = Carbon::createFromTimestamp(strtotime($request->input('openingDoorsDate')))->setTimeFromTimeString($request->input('openingDoorsTime'));
                } else {
                    $session->openingDoorsDate = null;
                }
            }
            if ($request->has('closingDoorsDate') && $request->has('closingDoorsTime')) {
                if ($request->input('closingDoorsDate') && $request->input('closingDoorsTime')) {
                    $session->closingDoorsDate = Carbon::createFromTimestamp(strtotime($request->input('closingDoorsDate')))->setTimeFromTimeString($request->input('closingDoorsTime'));
                } else {
                    $session->closingDoorsDate = null;
                }
            }
            // Partners
            if ($request->has('partners')) {
                $arrayToSync = [];
                foreach ($request->input('partners') as $partnerRequest) {
                    $arrayToSync[$partnerRequest['id']] = [
                        'commissionPercentage' => $partnerRequest['commissionPercentage'],
                        'commissionMinimum' => $partnerRequest['commissionMinimum'],
                        'commissionMaximum' => $partnerRequest['commissionMaximum'],
                    ];
                }
                $session->partners()->sync($arrayToSync);
            }
            // Fares
            if ($request->has('fares')) {
                foreach ($request->input('fares') as $fareRequest) {
                    if ($fareRequest['id']) {
                        $fare = Fare::find($fareRequest['id']);
                    } else {
                        $fare = new Fare();
                        $fare->session()->associate($session);
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
                    if ($fareRequest['ticketSeasonId']) {
                        $fare->ticketSeason()->associate($fareRequest['ticketSeasonId']);
                    }
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

    /**
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function sessionData(Request $request, Session $session): JsonResponse
    {
        $filterFare = $request->query('filterFare') !== 'false';
        // Space file, for the canvas
        $spaceFile = SavitarFile::whereFileableType(Space::class)->whereFileableId($session->spaceId)
            ->where('category', 'mainImage')
            ->first();
        $spaceFile ? $spaceImageUrl = $spaceFile->url : $spaceImageUrl = 'assets/aforo.svg';

        // SessionAreas with sessionSectors and fares
        $sessionAreas = $session->space->sessionAreas()
            ->where('sessionId', $session->id)
            ->with([
                'sessionSectors',
                'fares' => static function (BelongsToMany $query) use ($session, $filterFare) {
                    if (!$filterFare) {
                        $query->where('sessionId', $session->id)
                            ->where('isActive', true);
                    } else {
                        $query->leftJoin('Ticket', 'Ticket.sessionAreaFareId', '=', 'SessionAreaFare.id')
                            ->where('Fare.sessionId', $session->id)
                            ->where(static function(Builder $query) {
                                $query->where('restrictionStartDate', '<' , Carbon::now())
                                    ->orWhereNull('restrictionStartDate');
                            })
                            ->where(static function(Builder $query) {
                                $query->where('restrictionEndDate', '>' , Carbon::now())
                                    ->orWhereNull('restrictionEndDate');
                            })
                            ->havingRaw('count("Ticket"."id") < "Fare"."maxTickets" OR "Fare"."maxTickets" IS NULL')
                            ->where('isActive', true)
                            ->groupBy([
                                'Fare.id',
                                'SessionAreaFare.sessionAreaId',
                                'SessionAreaFare.fareId',
                                'SessionAreaFare.id'
                            ]);
                    }
                },
            ])->get();

        $sessionAreas->each(static function (SessionArea $sessionArea) use ($session) {
            $sessionArea->sessionSectors->each(static function (SessionSector $sessionSector) use ($session) {
                $sessionSector['freeSeats'] = $session->freeSessionSeats()->where('sessionSectorId', $sessionSector->id)->count() > 0;
                $sessionSector->makeHidden([
                    'name', 'ticketName', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
                ]);
            });
            $sessionArea->fares->each(static function (Fare $fare) {
                $fare->makeHidden([
                    'name', 'ticketName', 'sessionId', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
                ]);
            });
        });
        $sessionAreas->makeHidden([
            'name', 'ticketName', 'spaceId', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
        ]);

        // Return only numbered sessionSeats
        $sessionSeats = $session->sessionSeats()->with(['sessionSector', 'sessionDoors'])->get();
        $sessionSeats = $sessionSeats->filter(static function (SessionSeat $sessionSeat) {
            return $sessionSeat->sessionSector->isNumbered;
        });
        $sessionSeats->makeHidden([
            'sessionId', 'sessionSector', 'observations', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
        ]);

        if ($request->query('query') === 'with-session') {
            $session->makeHidden(['space', 'observations', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy']);
            return response()->json([
                'session' => $session,
                'spaceImageUrl' => $spaceImageUrl,
                'sessionAreas' => $sessionAreas,
                'sessionSeats' => $sessionSeats,
            ], 200);
        }
        return response()->json([
            'spaceImageUrl' => $spaceImageUrl,
            'sessionAreas' => $sessionAreas,
            'sessionSeats' => $sessionSeats,
        ], 200);
    }

    /**
     * Returns all sessionSeats.
     *
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function sessionSeats(Request $request, Session $session): JsonResponse
    {
        $sessionSeats = $session->sessionSeats;
        $sessionSeats->makeHidden(['createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy']);

        return response()->json(['sessionSeats' => $sessionSeats], 200);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @return array|bool|ResponseFactory|Response
     */
    public function pointOfSaleIndex(Request $request, PointOfSale $pointOfSale)
    {
        $this->setIndexOptionalConditions([
            ['column' => 'PointOfSale.id', 'operator' => '=', 'value' => $pointOfSale->id, 'relationShip' => 'pointsOfSale'],
            ['relationShip' => 'pointsOfSale', 'existence' => false],
        ]);
        return $this->index($request);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param Show $show
     * @return array|bool|ResponseFactory|Response
     */
    public function pointOfSaleShowIndex(Request $request, PointOfSale $pointOfSale, Show $show)
    {
        $this->setIndexConditions([
            ['column' => 'Session.showId', 'operator' => '=', 'value' => $show->id],
        ]);
        $this->setIndexOptionalConditions([
            ['column' => 'PointOfSale.id', 'operator' => '=', 'value' => $pointOfSale->id, 'relationShip' => 'pointsOfSale'],
            ['relationShip' => 'pointsOfSale', 'existence' => false],
        ]);
        return $this->index($request);
    }

    /**
     * @param Request $request
     * @param Account $account
     * @return mixed
     */
    public function accountIndex(Request $request, Account $account)
    {
        $this->setIndexConditions([
            ['column' => 'Show.accountId', 'operator' => '=', 'value' => $account->id, 'relationShip' => 'show'],
        ]);
        return $this->index($request);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function printModel(Request $request, Session $session): JsonResponse
    {
        $sessionPrintModel = $session->sessionPrintModel()->with('files')->first();
        return response()->json([
            'sessionPrintModel' => $sessionPrintModel,
        ], 200);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function updatePrintModel(Request $request, Session $session): JsonResponse
    {
        $printModel = $session->sessionPrintModel;
        if (!$printModel) {
            $printModel = new SessionPrintModel();
            $printModel->session()->associate($session);
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

    /**
     * Returns counts of the session.
     *
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function sessionCounts(Request $request, Show $show, Session $session): JsonResponse
    {
        return response()->json([
            "availableSessionSeats" => $session->availableSessionSeats()->count(),
            "soldSessionSeats" => $session->soldSessionSeats()->count(),
            "isForDisabledSessionSeats" => $session->isForDisabledSessionSeats()->count(),
            "hardTicketSessionSeats" => $session->hardTicketSessionSeats()->count()
        ], 200);
    }

    /**
     * The public index of the available shows.
     *
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function publicSession(Request $request, Session $session): JsonResponse
    {
        return \response()->json($session, 200);
    }

    /**
     * Returns session areas for given session (Public)
     *
     * @param Request $request
     * @param $sessionId
     * @return JsonResponse
     */
    public function publicSessionAreas(Request $request, $sessionId): JsonResponse
    {
        $sessionAreas = SessionArea::where('sessionId', '=', $sessionId)
            ->with([
                'sessionSectors',
                'fares' => static function (BelongsToMany $query) use ($sessionId) {
                    $query->leftJoin('Ticket', 'SessionAreaFare.id', 'Ticket.sessionAreaFareId')
                        ->where('Fare.sessionId', $sessionId)
                        ->where(static function(Builder $query) {
                            $query->where('restrictionStartDate', '<' , Carbon::now())
                                ->orWhereNull('restrictionStartDate');
                        })
                        ->where(static function(Builder $query) {
                            $query->where('restrictionEndDate', '>' , Carbon::now())
                                ->orWhereNull('restrictionEndDate');
                        })
                        ->where('isActive', true)
                        ->groupBy([
                            'Fare.id',
                            'SessionAreaFare.id',
                            'SessionAreaFare.sessionAreaId',
                            'SessionAreaFare.fareId'
                        ])
                        ->havingRaw('count("Ticket".id) < "Fare"."maxTickets" or "Fare"."maxTickets" is null');
                },
            ])->get();

        $sessionAreas->each(static function (SessionArea $sessionArea) use ($sessionId) {
            $sessionArea->sessionSectors->each(static function (SessionSector $sessionSector) use ($sessionId) {
                // $sessionSector['freeSeats'] = $session->freeSessionSeats()->where('sessionSectorId', $sessionSector->id)->count() > 0;
                $sessionSector['freeSeats'] =
                    DB::table('SessionSeat')->where('status', 'enabled')->where('sessionId', $sessionId)->first() !== [];
                $sessionSector->makeHidden([
                    'name', 'ticketName', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
                ]);
            });
            $sessionArea->fares->each(static function (Fare $fare) {
                $fare->makeHidden([
                    'name', 'ticketName', 'sessionId', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy',
                ]);
            });
        });

        return \response()->json($sessionAreas, 200);
    }

    /**
     * Returns session areas for given session (Public)
     *
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function publicSessionSpaceImage(Request $request, Session $session): JsonResponse
    {
        // Space file, for the canvas
        $spaceFile = SavitarFile::whereFileableType(Space::class)->whereFileableId($session->spaceId)
            ->where('category', 'mainImage')
            ->first();
        $spaceFile ? $spaceImageUrl = $spaceFile->url : $spaceImageUrl = 'assets/aforo.svg';

        return \response()->json($spaceImageUrl, 200);
    }

    /**
     * Returns session areas for given session (Public)
     *
     * @param Request $request
     * @param $sessionId
     * @param string $sessionSectorId
     * @return JsonResponse
     */
    public function publicSectorFreeSeats(Request $request, $sessionId, string $sessionSectorId): JsonResponse
    {
        // $freeSeats = $session->freeSessionSeats()->where('sessionSectorId', $sessionSectorId)->count() > 0;
        $freeSeats = DB::table('SessionSeat')
                ->where('status', 'enabled')
                ->where('sessionId', $sessionId)
                ->where('sessionSectorId', $sessionSectorId)
                ->first() !== [];
        return \response()->json($freeSeats, 200);
    }

    /**
     * Returns session seats for given session sector (Public)
     *
     * @param Request $request
     * @param $sessionSectorId
     * @return JsonResponse
     */
    public function publicSessionSectorSeats(Request $request, $sessionSectorId): JsonResponse
    {
        $sessionSeats = SessionSeat::where('sessionSectorId', $sessionSectorId)
            ->with(['sessionDoors'])
            ->get();

        return \response()->json($sessionSeats, 200);
    }

    /**
     * Returns session seats for given session sector (Public)
     *
     * @param Request $request
     * @param Session $session
     * @param $sessionSectorId
     * @return JsonResponse
     */
    public function sessionSectorSoldSeatsInfo(Request $request, Session $session, $sessionSectorId): JsonResponse
    {
        $sessionSeatsInfo = SessionSeat::leftJoin('User', 'User.email', '=', 'SessionSeat.updatedBy')
            ->leftJoin('Client', 'Client.userId', '=', 'User.id')
            ->where('SessionSeat.sessionSectorId', $sessionSectorId)
            ->whereIn('SessionSeat.status', ['sold', 'reserved'])
            ->select([
                'SessionSeat.id as sessionSeatId',
                'Client.name as clientName',
                'Client.surname as clientSurname',
                'SessionSeat.updatedAt as reserveDateStart'
            ])
            ->get();

        return \response()->json($sessionSeatsInfo, 200);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function purchaseReport(Request $request, Session $session): JsonResponse
    {
        $reportData = $this->getSessionPurchaseReport($session);

        return \response()->json($reportData);
    }

    public function getSessionPurchaseReport(Session $session): array
    {
        $reportByVia = [];
        $totalTickets = 0;
        $totalAmount = 0;
        $ordersVia = ['web', 'automatic', 'assisted', 'phone'];
        $sessionAreas = $session->sessionAreas;
        $sessionAreasIds = $sessionAreas->pluck('id');

        $sessionAreaFares = SessionAreaFare::whereIn('sessionAreaId', $sessionAreasIds)
            ->orderBy('sessionAreaId')->get();
        $sessionPlaceId = $session->placeId;

        $viaMap = [
            'web' => 'Web',
            'automatic' => 'Automática',
            'assisted' => 'Asistida',
            'phone' => 'Telefónica'
        ];

        foreach ($ordersVia as $via) {
            foreach ($sessionAreaFares as $sessionAreaFare) {
                $ticketOfficeInfo = Ticket::join('Order', 'Ticket.orderId', '=', 'Order.id')
                    ->join('PlacePointOfSale', 'PlacePointOfSale.pointOfSaleId', '=', 'Order.pointOfSaleId')
                    ->where('Ticket.sessionAreaFareId', '=', $sessionAreaFare->id)
                    ->where('PlacePointOfSale.placeId', '=', $sessionPlaceId)
                    ->where('Order.via', '=', $via)
                    ->selectRaw('COUNT("Ticket".id) as "ticketCount", SUM("Ticket"."amountWithDiscount") as amount')
                    ->first();

                $ticketEarlyInfo = Ticket::join('Order', 'Ticket.orderId', '=', 'Order.id')
                    ->leftJoin('PlacePointOfSale', 'PlacePointOfSale.pointOfSaleId', '=', 'Order.pointOfSaleId')
                    ->where('Ticket.sessionAreaFareId', '=', $sessionAreaFare->id)
                    ->where(static function (Builder $query) use ($sessionPlaceId) {
                        $query->where('PlacePointOfSale.placeId', '=', $sessionPlaceId)
                            ->orWhereNull('PlacePointOfSale.placeId');
                    })
                    ->where('Order.via', '=', $via)
                    ->selectRaw('COUNT("Ticket".id) as "ticketCount", SUM("Ticket"."amountWithDiscount") as amount')
                    ->first();

                if ($ticketEarlyInfo && (int)$ticketEarlyInfo['ticketCount'] > 0) {
                    $reportByVia[] = [
                        'via' => $viaMap[$via],
                        'sessionAreaName' => $sessionAreaFare->sessionArea->webName,
                        'fareName' => $sessionAreaFare->fare->webName,
                        'pvp' => $sessionAreaFare->earlyTotalPrice,
                        'ticketCount' => $ticketEarlyInfo['ticketCount'],
                        'amount' => $ticketEarlyInfo['amount'],
                    ];

                    $totalTickets += $ticketEarlyInfo['ticketCount'];
                    $totalAmount += $ticketEarlyInfo['amount'];
                }

                if ($ticketOfficeInfo && (int)$ticketOfficeInfo['ticketCount'] > 0) {
                    $reportByVia[$via][] = [
                        'via' => $viaMap[$via],
                        'sessionAreaName' => $sessionAreaFare->sessionArea->webName,
                        'fareName' => $sessionAreaFare->fare->webName,
                        'pvp' => $sessionAreaFare->ticketOfficeTotalPrice,
                        'ticketCount' => $ticketOfficeInfo['ticketCount'],
                        'amount' => $ticketOfficeInfo['amount'],
                    ];

                    $totalTickets += $ticketOfficeInfo['ticketCount'];
                    $totalAmount += $ticketOfficeInfo['amount'];
                }
            }
        }

        return [
            'reportLines' => $reportByVia,
            'totalTickets' => $totalTickets,
            'totalAmount' => $totalAmount,
        ];
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return mixed
     */
    public function downloadPurchaseReport(Request $request, Session $session)
    {
        $reportData = $this->getSessionPurchaseReport($session);
        $reportData['session'] = $session;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('sessions.purchase-report', $reportData);

        //return view('orders.tickets', ['ticket' => $ticket]);

        return $pdf->download('Hoja de Taquilla - Sesión: ' . $session->show->webName . ' ' . $session->date->toDateTimeString() . '.pdf');
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return JsonResponse
     */
    public function sessionDoors(Request $request, Session $session): JsonResponse
    {
        return \response()->json($session->sessionDoors()->orderBy('order')->get());
    }
}
