<?php

namespace App\Http\Controllers;

use App\Brand;
use App\PointOfSale;
use App\Show;
use App\TicketSeason;
use App\TicketVoucher;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Models\SavitarBuilder;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;
    use HidesAttributes;

    /**
     * ShowController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Show::class);

        $this->configureCRUD([
            'modelClass' => Show::class,
            'indexAppends' => [
                'sessions.place.province',
                'sessions.showTemplate'
            ],
            'showAppends' => [
                'sessions.place.province',
                'sessions.showTemplate',
                'account',
                'files',
                'brands'
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => Show::class,
            'dataGridTitle' => 'Eventos',
            'defaultOrderBy' => 'name',
        ]);
    }

    /**
     * @param Request $request
     * @param Show $show
     * @param $saveOrUpdate
     */
    protected function savingHook(Request $request, Show $show, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            // Brands
            if ($request->has('brands')) {
                $arrayToSync = [];
                /** @var Brand $brand */
                foreach ($request->input('brands') as $brandId) {
//                    $arrayToSync[] = $brand['id'];
                    $arrayToSync[] = $brandId;
                }
                $show->brands()->sync($arrayToSync);
            }
        }

    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @return array|bool|ResponseFactory|Response
     */
    public function pointOfSaleIndex(Request $request, PointOfSale $pointOfSale)
    {
        $models = Show::whereHas('sessions', static function (SavitarBuilder $query) use ($pointOfSale) {
            $query->where('status', '<>', 'Finalizada')
                ->where('isHidden', false)
                ->where(static function (SavitarBuilder $query2) use ($pointOfSale) {
                    $query2->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSale) {
                        $query2->where('PointOfSale.id', $pointOfSale->id);
                    })->orDoesntHave('pointsOfSale');
                });
        })
            ->whereHas('account', static function (SavitarBuilder $query) use ($pointOfSale) {
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSale) {
                    $query2->where('PointOfSale.id', $pointOfSale->id);
                })->orDoesntHave('pointsOfSale');
            });

        return $this->indexFunction($request, $models, $this->modelInstance->getKeyName(), $this->indexAttributes, $this->defaultOrderBy, $this->defaultSortOrder, $this->defaultPageSize);
    }

    /**
     * The public index of the available shows.
     *
     * @param Request $request
     * @param Brand|null $brand
     * @return JsonResponse
     */
    public function publicIndex(Request $request, Brand $brand = null): JsonResponse
    {
        if (isset($brand)) {
            // The shows associated to the brand
            $showsQuery = $brand->shows()->whereHas('sessions', static function (SavitarBuilder $query) {
                $query->where('status', '<>', 'Finalizada')
                    ->where('isActive', '<>', false)
                    ->where('isHidden', false);
            })
                ->whereHas('account', static function (SavitarBuilder $query) {
                    $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) {
                        $query2->where('slug', 'web-redentradas');
                    })->orDoesntHave('pointsOfSale');
                })
                ->join(\DB::raw('(
                                SELECT DISTINCT ON ("Session"."showId") "Session".* FROM "Session"
                                WHERE "status" <> \'Finalizada\'
                                  AND "isActive" <> FALSE
                                  AND "isHidden" = FALSE
                                ORDER BY "Session"."showId", "Session"."date"
                            ) AS "Sessions"'), static function (JoinClause $join) {
                    $join->on('Sessions.showId', '=', 'Show.id');
                })
                ->orderBy('Sessions.date', 'ASC')
                ->select('Show.*')
                ->with([
                    'sessions' => static function (HasMany $query) {
                        $query->where('status', '<>', 'Finalizada')
                            ->where('isHidden', false)
                            ->with([
                                'place' => static function (BelongsTo $query) {
                                    $query->select(['id', 'webName', 'provinceId', 'address', 'city', 'description', 'hasAccessControl', 'mapLink']);
                                },
                                'place.province' => static function (BelongsTo $query) {
                                    $query->select(['id', 'name', 'slug', 'type']);
                                },
                                'showTemplate' => static function (BelongsTo $query) {
                                    $query->select(['id', 'webName', 'description', 'duration', 'break', 'additionalInfo', 'videoId', 'showClassificationId'])
                                        ->with(['showClassification:id,name']);
                                },
                            ]);
                    },
                ]);
        } else {
            // The ones which have to be displayed on redentradas.com
            $showsQuery = Show::whereHas('sessions', static function (SavitarBuilder $query) {
                $query->where('status', '<>', 'Finalizada')
                    ->where('isActive', '<>', false)
                    ->where('isHidden', false)
                    ->where('appearsOnRedentradas', true);
            })
                ->whereHas('account', static function (SavitarBuilder $query) {
                    $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) {
                        $query2->where('slug', 'web-redentradas');
                    })->orDoesntHave('pointsOfSale');
                })
                ->join(\DB::raw('(
                                SELECT DISTINCT ON ("Session"."showId") "Session".* FROM "Session"
                                WHERE "status" <> \'Finalizada\'
                                  AND "isActive" <> FALSE
                                  AND "isHidden" = FALSE
                                ORDER BY "Session"."showId", "Session"."date"
                            ) AS "Sessions"'), static function (JoinClause $join) {
                    $join->on('Sessions.showId', '=', 'Show.id');
                })
                ->orderBy('Sessions.date', 'ASC')
                ->select('Show.*')
                ->with([
                    'sessions' => static function (HasMany $query) {
                        $query->where('status', '<>', 'Finalizada')
                            ->where('isHidden', false)
                            ->with([
                                'place' => static function (BelongsTo $query) {
                                    $query->select(['id', 'webName', 'provinceId', 'address', 'city', 'description', 'hasAccessControl', 'mapLink']);
                                },
                                'place.province' => static function (BelongsTo $query) {
                                    $query->select(['id', 'name', 'slug', 'type']);
                                },
                                'showTemplate' => static function (BelongsTo $query) {
                                    $query->select(['id', 'webName', 'description', 'duration', 'break', 'additionalInfo', 'videoId', 'showClassificationId'])
                                        ->with(['showClassification:id,name']);
                                },
                            ]);
                    },
                ]);
        }

        $page = (int) $request->query('page');
        $pageSize = (int) $request->query('pageSize');

        if ($page && $pageSize) {
            $showsQuery = $showsQuery->limit($pageSize)->offset($pageSize * ($page - 1));
        }

        $shows = $showsQuery->get();

        return response()->json(['shows' => $shows], 200);
    }

    /**
     * The public index of the available shows.
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function publicShow(Request $request, string $slug): JsonResponse
    {
        $show = Show::with([
            'sessions' => static function (HasMany $query) {
                $query->where('status', '<>', 'Finalizada')
                    ->where('isHidden', false)
                    ->with([
                        'place' => static function (BelongsTo $query) {
                            $query->select(['id', 'webName', 'provinceId', 'address', 'city', 'description', 'hasAccessControl', 'mapLink']);
                        },
                        'place.province' => static function (BelongsTo $query) {
                            $query->select(['id', 'name', 'slug', 'type']);
                        },
                        'showTemplate' => static function (BelongsTo $query) {
                            $query->select(['id', 'webName', 'description', 'duration', 'break', 'additionalInfo', 'videoId', 'showClassificationId'])
                                ->with(['showClassification:id,name']);
                        },
                    ]);
            },
        ])
            ->where('slug', '=', $slug)
            ->first();
        return \response()->json($show);
    }

    /**
     * Show by old id
     *
     * @param Request $request
     * @param string $oldId
     * @return JsonResponse
     */
    public function publicShowByOldId(Request $request, string $oldId): JsonResponse
    {
        $show = Show::where('oldId', '=', $oldId)->firstOrFail();
        return \response()->json($show);
    }

    /**
     * Returns featured shows (Public)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function featuredShows(Request $request): JsonResponse
    {
        $shows = Show::where('isFeatured', true)->get();

        return \response()->json($shows);
    }

    /**
     * The available shows for the access control.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws JWTException
     */
    public function accessIndex(Request $request): JsonResponse
    {
        $payload = JWTAuth::parseToken()->getPayload();

        $searchQuery = $request->query('query');
        $showsQuery = Show::whereHas('sessions', static function (SavitarBuilder $query) {
            $query->where('status', '<>', 'Finalizada')
                ->where('isActive', '<>', false)
                ->where('isHidden', false);
        })
            ->select(['Show.id', 'Show.webName'])
            ->with([
                'sessions' => static function (HasMany $query) use ($searchQuery) {
                    $query->where('status', '<>', 'Finalizada')
                        ->where('isHidden', false)
                        ->withCount(['soldSessionSeats'])
                        ->with([
                            'place' => static function (BelongsTo $query) {
                                $query->select(['id', 'webName', 'provinceId', 'address', 'city', 'description', 'hasAccessControl']);
                            },
                            'place.province' => static function (BelongsTo $query) {
                                $query->select(['id', 'name', 'slug', 'type']);
                            },
                            'showTemplate' => static function (BelongsTo $query) {
                                $query->select(['id', 'webName', 'duration', 'break', 'additionalInfo', 'showClassificationId'])
                                    ->with(['showClassification:id,name']);
                            },
                        ]);
                    if ($searchQuery) {
                        $query->whereHas('place', static function (SavitarBuilder $query) use ($searchQuery) {
                            $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                        })
                            ->orWhereHas('showTemplate', static function (SavitarBuilder $query) use ($searchQuery) {
                                $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                            });
                    }
                },
            ]);

        if ($searchQuery) {
            $showsQuery->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%')
                ->orWhereHas('sessions', static function (SavitarBuilder $query) use ($searchQuery) {
                    $query->whereHas('place', static function (SavitarBuilder $query) use ($searchQuery) {
                        $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                    })
                        ->orWhereHas('showTemplate', static function (SavitarBuilder $query) use ($searchQuery) {
                            $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                        });
                });
        }

        if ($payload["accountId"]) {
            $showsQuery->where('accountId', $payload["accountId"]);
        }
        $shows = $showsQuery->get();

        $shows = $shows->sortBy(static function ($show) {
            return $show['sessions'][0]->date;
        })->values();

        // Add successful accesses count / total
        foreach ($shows as $show) {
            foreach ($show->sessions as $session) {
                $session['successfulAccessesCheckCount'] = $session->successfulAccessesCheckCount();
            }
        }
        return response()->json(['shows' => $shows], 200);
    }

    /**
     * The past shows for the access control.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function accessHistoryIndex(Request $request): JsonResponse
    {
        $payload = JWTAuth::parseToken()->getPayload();

        $searchQuery = $request->query('query');
        $showsQuery = Show::whereHas('sessions', static function (SavitarBuilder $query) {
            $query->where('status', '=', 'Finalizada')
                ->where('isActive', '<>', false)
                ->where('isHidden', false);
        })
            ->select(['Show.id', 'Show.webName'])
            ->with([
                'sessions' => static function (HasMany $query) use ($searchQuery) {
                    $query->where('status', '=', 'Finalizada')
                        ->where('isHidden', false)
                        ->withCount(['soldSessionSeats'])
                        ->with([
                            'place' => static function (BelongsTo $query) {
                                $query->select(['id', 'webName', 'provinceId', 'address', 'city', 'description', 'hasAccessControl']);
                            },
                            'place.province' => static function (BelongsTo $query) {
                                $query->select(['id', 'name', 'slug', 'type']);
                            },
                            'showTemplate' => static function (BelongsTo $query) {
                                $query->select(['id', 'webName', 'duration', 'break', 'additionalInfo', 'videoId', 'showClassificationId'])
                                    ->with(['showClassification:id,name']);
                            },
                        ]);
                    if ($searchQuery) {
                        $query->whereHas('place', static function (SavitarBuilder $query) use ($searchQuery) {
                            $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                        })
                            ->orWhereHas('showTemplate', static function (SavitarBuilder $query) use ($searchQuery) {
                                $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                            });
                    }
                },
            ]);

        if ($searchQuery) {
            $showsQuery->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%')
                ->orWhereHas('sessions', static function (SavitarBuilder $query) use ($searchQuery) {
                    $query->whereHas('place', static function (SavitarBuilder $query) use ($searchQuery) {
                        $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                    })
                        ->orWhereHas('showTemplate', static function (SavitarBuilder $query) use ($searchQuery) {
                            $query->where(DB::raw('unaccent(' . '"webName"' . ')'), 'ILIKE', '%' . $searchQuery . '%');
                        });
                });
        }
        if ($payload["accountId"]) {
            $showsQuery->where('accountId', $payload["accountId"]);
        }
        $shows = $showsQuery->get();

        $shows = $shows->sortBy(static function ($show) {
            return $show['sessions'][0]->date;
        })->values();

        // Add successful accesses count / total
        foreach ($shows as $show) {
            foreach ($show->sessions as $session) {
                $session['successfulAccessesCheckCount'] = $session->successfulAccessesCheckCount();
            }
        }
        return response()->json(['shows' => $shows], 200);
    }

    /**
     * Return the available seasons and vouchers.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function checkForSeasonsAndVouchers(string $slug): JsonResponse
    {
        $show = Show::where('slug', '=', $slug)->firstOrFail();
        $sessions = $show->sessions()->where('status', '<>', 'Finalizada')
            ->where('isActive', '<>', false)
            ->where('isHidden', false)
            ->select('id')
            ->get();
        $sessionIds = $sessions->pluck('id');
        $ticketSeasons = TicketSeason::whereHas('sessions', static function (SavitarBuilder $query) use ($sessionIds) {
            $query->whereIn('sessionId', $sessionIds);
        })->get();
        $ticketVouchers = TicketVoucher::whereHas('sessions', static function (SavitarBuilder $query) use ($sessionIds) {
            $query->whereIn('sessionId', $sessionIds);
        })->get();

        return response()->json([
            'ticketSeasons' => $ticketSeasons,
            'ticketVouchers' => $ticketVouchers
        ], 200);
    }


    /**
     * @throws Exception
     */
    public static function fillShowSlugs(): void
    {
        echo "Generando slugs...\n";
        Show::whereNull('slug')->orderBy('createdAt')->chunk(500, static function ($shows) {
            foreach ($shows as $show) {
                $slug = Str::slug($show->webName);
                $existingShow = Show::where('slug', '=', $slug)->first();

                if ($existingShow) {
                    $slug .= '-' . random_int(1, 999999);
                }
                echo "Slug generado: " . $slug . "\n";
                DB::table('Show')->where('id', '=', $show->id)->update(['slug' => $slug]);
            }
        });
    }
}
