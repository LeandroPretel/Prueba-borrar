<?php

namespace App\Http\Controllers;

use App\Brand;
use App\ClientDeliveryAddress;
use App\Show;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;

class BrandController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * BrandController constructor.
     */
    public function __construct()
    {
        $this->customFillArrayOptions();
        $this->initAuthorization(Brand::class);
        $this->configureCRUD([
            'modelClass' => Brand::class,
            'indexAppends' => [
            ],
            'showAppends' => [
                'files',
                'province'
            ],
        ]);
        $this->configureDataGrid([
            'modelClass' => Brand::class,
            'dataGridTitle' => 'Marcas blancas',
        ]);
    }

    /**
     * @param Request $request
     * @param Brand $brand
     * @param $saveOrUpdate
     */
    protected function savingHook(Request $request, Brand $brand, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            if ($request->has('provinceId')) {
                $brand->provinceId = $request->input('provinceId');
            }
        }
    }

    /**
     * @param Request $request
     * @param Brand $brand
     * @param $saveOrUpdate
     */
    protected function savedHook(Request $request, Brand $brand, $saveOrUpdate): void
    {
        if ($saveOrUpdate) {
            // Shows
            if (isset($request['associatedToRedentradasShows']) && $request->input('associatedToRedentradasShows') == true) {
                // Associate to the Brand all the shows which have appearsOnRedentradas == true
                $arrayToSync = [];
                $brandCurrentShowsIds = $brand->shows()->pluck('Show.id');
                foreach (Show::all() as $show) {
                    if ($show->appearsOnRedentradas) {
                        if (!$brandCurrentShowsIds->contains($show->id)) {
                            $arrayToSync[$show->id] = [
                                'associatedToRedentradas' => true
                            ];
                        }
                    }
                }

                $brand->shows()->sync($arrayToSync);

//                $arrayToSync = [];
//                foreach ($fareRequest['sessionAreas'] as $sessionAreaRequest) {
//                    $arrayToSync[$sessionAreaRequest['sessionAreaId']] = [
//                        'isActive' => $sessionAreaRequest['isActive'],
//                        'earlyPrice' => $sessionAreaRequest['earlyPrice'],
//                        'earlyDistributionPrice' => $sessionAreaRequest['earlyDistributionPrice'],
//                        'earlyTotalPrice' => $sessionAreaRequest['earlyPrice'] + $sessionAreaRequest['earlyDistributionPrice'],
//                        'ticketOfficePrice' => $sessionAreaRequest['ticketOfficePrice'],
//                        'ticketOfficeDistributionPrice' => $sessionAreaRequest['ticketOfficeDistributionPrice'],
//                        'ticketOfficeTotalPrice' => $sessionAreaRequest['ticketOfficePrice'] + $sessionAreaRequest['ticketOfficeDistributionPrice'],
//                    ];
//                }
//                $fare->sessionAreas()->sync($arrayToSync);
            } else if (isset($request['associatedToRedentradasShows']) && $request->input('associatedToRedentradasShows') == false) {
                // Disassociate to the Brand all the shows which have appearsOnRedentradas == true. This leaves associated to the brand
                // the shows associated manually
                $arrayToSync = [];

                foreach ($brand->shows()->get() as $show) {
                    if ($show['pivot']['associatedToRedentradas'] == false) {
//                        dd($brand->shows->toArray(), $show['pivot']['associatedToRedentradas'] == false);
                        $arrayToSync[$show->id] = [
                            'associatedToRedentradas' => false
                        ];
                    }
                }
                $brand->shows()->sync($arrayToSync);
            }
        }
    }

    public function customFillArrayOptions(): void
    {
        foreach (Brand::$availableThemes as $availableTheme => $availableThemeColor) {
            $this->dataGridHeaders['selectedTheme']['possibleValues'][] = $availableTheme;
            $this->dataGridHeaders['selectedTheme']['configuration'][$availableTheme] = [];
            $this->dataGridHeaders['selectedTheme']['configuration'][$availableTheme]['html'] = 'Color corporativo';
            $this->dataGridHeaders['selectedTheme']['configuration'][$availableTheme]['translation'] = 'Color corporativo';
            $this->dataGridHeaders['selectedTheme']['configuration'][$availableTheme]['customColor'] = $availableThemeColor;
        }
    }

    /**
     * Check if domain already exist
     *
     * @param $brandDomain
     * @return JsonResponse
     */
    public function checkDomain($brandDomain)
    {
        $brand = Brand::withTrashed()->where('domain', $brandDomain)->with('files')->first();
        if ($brand) {
            $check = true;
            $domain = $brand->domain;
        } else {
            $check = false;
            $domain = null;
        }
        return response()->json(['check' => $check, 'domain' => $domain, 'brand' => $brand]);
    }
}
