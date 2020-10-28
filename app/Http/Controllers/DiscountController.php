<?php

namespace App\Http\Controllers;

use App\Discount;
use App\PointOfSale;
use App\Session;
use App\TicketSeason;
use App\TicketVoucher;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Savitar\Models\SavitarBuilder;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class DiscountController extends Controller
{
    use CRUD;
    use DataGrid;
    use Authorization;

    /**
     * DiscountController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Discount::class);

        $this->configureCRUD([
            'modelClass' => Discount::class,
            'indexConditions' => [
                ['column' => 'isActive', 'operator' => '=', 'value' => true],
            ],
            'showAppends' => [
                'sessions.showTemplate',
                'pointsOfSale',
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => Discount::class,
            'defaultOrderBy' => 'name',
            'dataGridTitle' => 'Descuentos',
        ]);
    }

    /**
     * @param Request $request
     * @param Discount $discount
     * @param $saveOrUpdate
     */
    public function savingHook(Request $request, Discount $discount, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            // Set the start and end date attributes
            if ($request->has('startDate') && $request->has('startDateTime')) {
                if ($request->input('startDate')) {
                    if ($request->input('startDateTime')) {
                        $discount->startDate = Carbon::createFromTimestamp(strtotime($request->input('startDate')))->setTimeFromTimeString($request->input('startDateTime'));
                    } else {
                        $discount->startDate = Carbon::createFromTimestamp(strtotime($request->input('startDate')));
                    }
                } else {
                    $discount->startDate = null;
                }
            }
            if ($request->has('endDate') && $request->has('endDateTime')) {
                if ($request->input('endDate')) {
                    if ($request->input('endDateTime')) {
                        $discount->endDate = Carbon::createFromTimestamp(strtotime($request->input('endDate')))->setTimeFromTimeString($request->input('endDateTime'));
                    } else {
                        $discount->endDate = Carbon::createFromTimestamp(strtotime($request->input('endDate')));
                    }
                } else {
                    $discount->endDate = null;
                }
            }

            // Check if there is numberOfCodes. Assign the code to the first discount (the original)
            if ($request->has('numberOfCodes') && $request->has('codes') && $request->input('codes.0')) {
                $discount->code = $request->input('codes.0');
            }
        }
    }

    public function savedHook(Request $request, Discount $discount, string $saveOrUpdate): void
    {
        // Check if there is numberOfCodes && we have to create multiple discounts
        if ($saveOrUpdate && $request->has('numberOfCodes') && $request->input('numberOfCodes') > 1) {
            $numberOfCodes = $request->input('numberOfCodes');
            for ($i = 1; $i < $numberOfCodes; $i++) {
                // Creates a new discount with the same attributes as the first one, only assigning the code if necessary
                $newDiscount = $discount->replicate();
                if ($request->has('codes') && $request->input('codes.' . $i)) {
                    $newDiscount->code = $request->input('codes.' . $i);
                }
                $newDiscount->save();

                if ($discount->sessions->count() > 0) {
                    // Associate the sessions if any to the other codes
                    $newDiscount->sessions()->sync($discount->sessions);
                }
                if ($discount->pointsOfSale->count() > 0) {
                    // Associate the pointsOfSale if any to the other codes
                    $newDiscount->pointsOfSale()->sync($discount->pointsOfSale);
                }
            }
        }
    }

    /**
     * A new replicated discount has to be used 0 times.
     *
     * @param Request $request
     * @param Discount $original
     * @param Discount $replica
     */
    public function replicatingHook(Request $request, Discount $original, Discount $replica): void
    {
        $replica->timesUsed = 0;
    }

    /**
     * @param Request $request
     * @param Discount $original
     * @param Discount $replica
     */
    public function replicatedHook(Request $request, Discount $original, Discount $replica): void
    {
        if ($original->sessions->count() > 0) {
            $replica->sessions()->sync($original->sessions);
        }
        if ($original->pointsOfSale->count() > 0) {
            $replica->pointsOfSale()->sync($original->pointsOfSale);
        }
    }

    /**
     * @param Request $request
     * @param $discountCode
     * @param Session $session
     * @param PointOfSale|null $pointOfSale
     * @return JsonResponse
     */
    public function check(Request $request, $discountCode, Session $session, ?PointOfSale $pointOfSale = null): JsonResponse
    {
        $check = false;
        $discount = Discount::whereCode($discountCode)
            ->where('isActive', true)
            ->whereType('discount')
            ->where(static function (SavitarBuilder $query) use ($session) {
                $query->whereHas('sessions', static function (SavitarBuilder $query2) use ($session) {
                    $query2->where('id', '=', $session->id);
                })
                    ->orDoesntHave('sessions');
            })
            ->where(static function (SavitarBuilder $query) use ($pointOfSale) {
                $pointOfSaleId = $pointOfSale ? $pointOfSale->id : null;
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSaleId) {
                    $query2->where('id', '=', $pointOfSaleId);
                })
                    ->orDoesntHave('pointsOfSale');
            })
            ->first();

        if ($discount && (!$discount->maximumUses || $discount->timesUsed < $discount->maximumUses)) {
            $check = true;
        }

        return response()->json(['check' => $check, 'discount' => $discount]);
    }

    /**
     * @param Request $request
     * @param $discountCode
     * @param TicketSeason $ticketSeason
     * @param PointOfSale|null $pointOfSale
     * @return JsonResponse
     */
    public function checkSeason(Request $request, $discountCode, TicketSeason $ticketSeason, ?PointOfSale $pointOfSale = null): JsonResponse
    {
        $check = false;
        $discount = Discount::whereCode($discountCode)
            ->where('isActive', true)
            ->whereType('discount')
            ->where(static function (SavitarBuilder $query) use ($ticketSeason) {
                $sessions = $ticketSeason->sessions;
                foreach ($sessions as $session) {
                    $query->whereHas('sessions', static function (SavitarBuilder $query2) use ($session) {
                        $query2->where('id', '=', $session->id);
                    });
                }
                $query->orDoesntHave('sessions');
            })
            ->where(static function (SavitarBuilder $query) use ($pointOfSale) {
                $pointOfSaleId = $pointOfSale ? $pointOfSale->id : null;
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSaleId) {
                    $query2->where('id', '=', $pointOfSaleId);
                })
                    ->orDoesntHave('pointsOfSale');
            })
            ->first();

        if ($discount && (!$discount->maximumUses || $discount->timesUsed < $discount->maximumUses)) {
            $check = true;
        }

        return response()->json(['check' => $check, 'discount' => $discount]);
    }

    /**
     * @param Request $request
     * @param $discountCode
     * @param TicketVoucher $ticketVoucher
     * @param PointOfSale|null $pointOfSale
     * @return JsonResponse
     */
    public function checkVoucher(Request $request, $discountCode, TicketVoucher $ticketVoucher, ?PointOfSale $pointOfSale = null): JsonResponse
    {
        $check = false;
        $discount = Discount::whereCode($discountCode)
            ->where('isActive', true)
            ->whereType('discount')
            ->where(static function (SavitarBuilder $query) use ($ticketVoucher) {
                $sessions = $ticketVoucher->sessions;
                foreach ($sessions as $session) {
                    $query->whereHas('sessions', static function (SavitarBuilder $query2) use ($session) {
                        $query2->where('id', '=', $session->id);
                    });
                }
                $query->orDoesntHave('sessions');
            })
            ->where(static function (SavitarBuilder $query) use ($pointOfSale) {
                $pointOfSaleId = $pointOfSale ? $pointOfSale->id : null;
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSaleId) {
                    $query2->where('id', '=', $pointOfSaleId);
                })
                    ->orDoesntHave('pointsOfSale');
            })
            ->first();

        if ($discount && (!$discount->maximumUses || $discount->timesUsed < $discount->maximumUses)) {
            $check = true;
        }

        return response()->json(['check' => $check, 'discount' => $discount]);
    }

    /**
     * @param Request $request
     * @param $amountToCheck
     * @param Session $session
     * @param PointOfSale|null $pointOfSale
     * @return JsonResponse
     */
    public function lookForPromotion(Request $request, $amountToCheck, Session $session, ?PointOfSale $pointOfSale = null): JsonResponse
    {
        $bestPromotion = null;
        if (!$pointOfSale) {
            $pointOfSale = PointOfSale::whereSlug('web-redentradas')->first();
        }
        $promotions = Discount::whereType('promotion')
            ->where('isActive', true)
            ->where(static function (SavitarBuilder $query) use ($session) {
                $query->whereHas('sessions', static function (SavitarBuilder $query2) use ($session) {
                    $query2->where('id', '=', $session->id);
                })
                    ->orDoesntHave('sessions');
            })
            ->where(static function (SavitarBuilder $query) use ($pointOfSale) {
                $pointOfSaleId = $pointOfSale ? $pointOfSale->id : null;
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSaleId) {
                    $query2->where('id', '=', $pointOfSaleId);
                })
                    ->orDoesntHave('pointsOfSale');
            })
            ->get();

        $bestAmount = 0;
        foreach ($promotions as $promotion) {
            // Look for the best promotion (higher discount)
            if ($promotion->isPercentage) {
                $promotionAmount = $amountToCheck * $promotion->amount / 100;
            } else {
                $promotionAmount = $promotion->amount;
            }
            if ($promotionAmount >= $bestAmount) {
                $bestPromotion = $promotion;
                $bestAmount = $promotionAmount;
            }
        }

        return response()->json(['promotion' => $bestPromotion]);
    }

    /**
     * @param Request $request
     * @param $amountToCheck
     * @param TicketSeason $ticketSeason
     * @param PointOfSale|null $pointOfSale
     * @return JsonResponse
     */
    public function lookForPromotionSeason(Request $request, $amountToCheck, TicketSeason $ticketSeason, ?PointOfSale $pointOfSale = null): JsonResponse
    {
        $bestPromotion = null;
        if (!$pointOfSale) {
            $pointOfSale = PointOfSale::whereSlug('web-redentradas')->first();
        }
        $promotions = Discount::whereType('promotion')
            ->where('isActive', true)
            ->where(static function (SavitarBuilder $query) use ($ticketSeason) {
                $sessions = $ticketSeason->sessions;
                foreach ($sessions as $session) {
                    $query->whereHas('sessions', static function (SavitarBuilder $query2) use ($session) {
                        $query2->where('id', '=', $session->id);
                    });
                }
                $query->orDoesntHave('sessions');
            })
            ->where(static function (SavitarBuilder $query) use ($pointOfSale) {
                $pointOfSaleId = $pointOfSale ? $pointOfSale->id : null;
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSaleId) {
                    $query2->where('id', '=', $pointOfSaleId);
                })
                    ->orDoesntHave('pointsOfSale');
            })
            ->get();

        $bestAmount = 0;
        foreach ($promotions as $promotion) {
            // Look for the best promotion (higher discount)
            if ($promotion->isPercentage) {
                $promotionAmount = $amountToCheck * $promotion->amount / 100;
            } else {
                $promotionAmount = $promotion->amount;
            }
            if ($promotionAmount >= $bestAmount) {
                $bestPromotion = $promotion;
                $bestAmount = $promotionAmount;
            }
        }

        return response()->json(['promotion' => $bestPromotion]);
    }

    /**
     * @param Request $request
     * @param $amountToCheck
     * @param TicketVoucher $ticketVoucher
     * @param PointOfSale|null $pointOfSale
     * @return JsonResponse
     */
    public function lookForPromotionVoucher(Request $request, $amountToCheck, TicketVoucher $ticketVoucher, ?PointOfSale $pointOfSale = null): JsonResponse
    {
        $bestPromotion = null;
        if (!$pointOfSale) {
            $pointOfSale = PointOfSale::whereSlug('web-redentradas')->first();
        }
        $promotions = Discount::whereType('promotion')
            ->where('isActive', true)
            ->where(static function (SavitarBuilder $query) use ($ticketVoucher) {
                $sessions = $ticketVoucher->sessions;
                foreach ($sessions as $session) {
                    $query->whereHas('sessions', static function (SavitarBuilder $query2) use ($session) {
                        $query2->where('id', '=', $session->id);
                    });
                }
                $query->orDoesntHave('sessions');
            })
            ->where(static function (SavitarBuilder $query) use ($pointOfSale) {
                $pointOfSaleId = $pointOfSale ? $pointOfSale->id : null;
                $query->whereHas('pointsOfSale', static function (SavitarBuilder $query2) use ($pointOfSaleId) {
                    $query2->where('id', '=', $pointOfSaleId);
                })
                    ->orDoesntHave('pointsOfSale');
            })
            ->get();

        $bestAmount = 0;
        foreach ($promotions as $promotion) {
            // Look for the best promotion (higher discount)
            if ($promotion->isPercentage) {
                $promotionAmount = $amountToCheck * $promotion->amount / 100;
            } else {
                $promotionAmount = $promotion->amount;
            }
            if ($promotionAmount >= $bestAmount) {
                $bestPromotion = $promotion;
                $bestAmount = $promotionAmount;
            }
        }

        return response()->json(['promotion' => $bestPromotion]);
    }

    /**
     * @param Discount $discount
     * @return bool
     */
    public function useDiscount(Discount $discount): bool
    {
        if (!$discount->maximumUses || $discount->timesUsed < $discount->maximumUses) {
            $discount->timesUsed++;
            $discount->save();
            Log::info('Se ha usado el descuento/promoción ' . $discount->name);
            return true;
        }
        Log::info('Se ha intentado usar el descuento/promoción ' . $discount->name . ' sin usos disponibles.');
        return false;
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleIndex(Request $request, PointOfSale $pointOfSale)
    {
        $this->setIndexConditions([
            ['column' => 'id', 'operator' => '=', 'value' => $pointOfSale->id, 'relationShip' => 'pointsOfSale'],
            ['relationShip' => 'pointsOfSale', 'existence' => false],
        ]);
        return $this->index($request);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleDataGrid(Request $request, PointOfSale $pointOfSale)
    {
        $this->setDataGridOptionalConditions([
            ['column' => 'id', 'operator' => '=', 'value' => $pointOfSale->id, 'relationShip' => 'pointsOfSale'],
            ['relationShip' => 'pointsOfSale', 'existence' => false],
        ]);
        return $this->dataGrid($request);
    }
}
