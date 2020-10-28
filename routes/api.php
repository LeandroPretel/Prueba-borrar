<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

use App\Area;
use App\Artist;
use App\Brand;
use App\Client;
use App\Discount;
use App\Door;
use App\Enterprise;
use App\Fare;
use App\Order;
use App\OrderReturn;
use App\OrderReturnReason;
use App\Partner;
use App\Place;
use App\PointOfSaleUser;
use App\Sector;
use App\Session;
use App\Show;
use App\ShowCategory;
use App\ShowClassification;
use App\ShowTemplate;
use App\Space;
use App\Ticket;
use App\TicketSeason;
use App\TicketSeasonGroup;
use App\TicketVoucher;
use Savitar\Models\SavitarRouter;
Route::group(['prefix' => 'v1/anfix'], static function () {
    Route::get('test', 'AnfixController@test')->name('test-anfix');
});

Route::get('v1/test/old-db', 'OldDbController@testConnection')->name('test-old-redentradas');
Route::get('v1/test/log', 'OldDbController@testLog')->name('test-log');
Route::get('v1/test/ticket/{ticket}', 'TicketController@downloadPhysicalTicket')->name('test-physical-ticket');

// Route::get('test/orders/{order}', 'OrderController@downloadTickets')->name('test');
// Route::get('test', 'OrderReturnController@test')->name('test');
// Route::get('test3', 'SessionSeatStatusController');

Route::get('v1/clients/check/{nif}', 'ClientController@checkNif')->name('clients-check-nif');
//Route::get('v1/enterprises/check/{nif}', 'EntepriseController@checkNif')->name('enterprises-check-nif');
// Routes to manage access control by locators
Route::get('v1/search-ticket/{locator}', 'TicketController@findByLocator')->name('ticket-find-by-locator');
Route::get('v1/search-order/{locator}', 'OrderController@findByLocator')->name('order-find-by-locator');

Route::post('v1/consultations', 'ConsultationController@store')->name('consultations.store');
Route::get('v1/shows-public-index', 'ShowController@publicIndex')->name('shows.public-index');
Route::get('v1/shows-public-index/{brand}', 'ShowController@publicIndex')->name('shows.public-index-brand');
Route::get('v1/shows-public/{slug}', 'ShowController@publicShow')->name('shows.public-show');
Route::get('v1/shows-public-by-old-id/{oldId}', 'ShowController@publicShowByOldId')->name('shows.public-show-by-old-id');
Route::get('v1/public/shows-featured', 'ShowController@featuredShows')->name('featured-shows.public');
Route::get('v1/check-for-seasons-and-vouchers/{slug}', 'ShowController@checkForSeasonsAndVouchers')->name('check-for-seasons-and-vouchers');
Route::get('v1/ticket-seasons-public/{ticketSeason}', 'TicketSeasonController@publicShow')->name('ticket-seasons.public-show');
Route::get('v1/ticket-vouchers-public/{ticketVoucher}', 'TicketVoucherController@publicShow')->name('ticket-voucher.public-show');
Route::get('v1/sessions/{session}/data', 'SessionController@sessionData')->name('session-data');
Route::get('v1/sessions/{session}/seats', 'SessionController@sessionSeats')->name('session-seats');

Route::get('v1/multiple-sessions/{ticketSeason}/{sectorName}/seats/', 'SessionSeatController@multiSessionsSeats')->name('multi-session-seats');

// Reserve numbered seats
Route::patch('v1/sessions/{session}/seats/{sessionSeat}', 'SessionSeatController@sessionSeatStatus')->name('session-seat-status');
Route::patch('v1/sessions/{sessionId}/session-sectors/{sessionSectorId}/seats/{row}/{column}', 'SessionSeatController@sessionSeatStatusByRowAndColumn')->name('session-seat-status-by-row-and-column');
Route::patch('v1/multiple-sessions/seats/{row}/{column}', 'SessionSeatController@sessionSeatStatusSessions')->name('session-seat-status-sessions-by-row-and-column');

// Reserve not-numbered seats
Route::patch('v1/sessions/{sessionId}/session-sectors/{sessionSectorId}/not-numbered-seat/reserve', 'SessionSeatController@reserveNotNumberedSeat')->name('session-seat-not-numbered-seat');
Route::patch('v1/multiple-sessions/session-sectors/not-numbered-seat/reserve', 'SessionSeatController@reserveNotNumberedSeatSessions')->name('session-seat-not-numbered-seat-sessions');
// Free Session Seats
Route::patch('v1/free-session-seats', 'SessionSeatController@freeSessionSeats')->name('session-free-session-seats');

Route::get('v1/points-of-sale/public-index', 'PointOfSaleController@publicIndex')->name('points-of-sale.public-index');
// Register
Route::post('v1/clients/register', 'ClientController@clientRegister')->name('client-register');

// Redsys
Route::get('v1/rok', 'OrderController@redsysOk')->name('redsys-ok');
Route::get('v1/rerr', 'OrderController@redsysError')->name('redsys-error');
Route::post('v1/rnotification', 'OrderController@redsysNotification')->name('redsys-notification');
// Returns redsys
Route::get('v1/orok', 'OrderReturnController@redsysOk')->name('redsys-return-ok');
Route::get('v1/orerr', 'OrderReturnController@redsysError')->name('redsys-return-error');
Route::post('v1/ornotification', 'OrderReturnController@redsysNotification')->name('redsys-return-notification');
//Route::get('v1/clients/{client}/orders/amount/{amount}/{discountId?}', 'OrderController@redsys');

// Discounts
Route::get('v1/discounts/check/{discountCode}/sessions/{session}/{pointOfSale?}', 'DiscountController@check')->name('check-discount');
Route::get('v1/discounts/check/{discountCode}/ticket-seasons/{ticketSeason}/{pointOfSale?}', 'DiscountController@checkSeason')->name('check-discount-season');
Route::get('v1/discounts/check/{discountCode}/ticket-vouchers/{ticketVoucher}/{pointOfSale?}', 'DiscountController@checkVoucher')->name('check-discount-voucher');

Route::get('v1/discounts/look-for-promotion/{amountToCheck}/sessions/{session}/{pointOfSale?}', 'DiscountController@lookForPromotion')->name('look-for-promotion');
Route::get('v1/discounts/look-for-promotion/{amountToCheck}/ticket-seasons/{ticketSeason}/{pointOfSale?}', 'DiscountController@lookForPromotionSeason')->name('look-for-promotion-season');
Route::get('v1/discounts/look-for-promotion/{amountToCheck}/ticket-vouchers/{ticketVoucher}/{pointOfSale?}', 'DiscountController@lookForPromotionVoucher')->name('look-for-promotion-voucher');

// Brands (domains)
Route::get('v1/brands/check-domain/{brandDomain}', 'BrandController@checkDomain')->name('checkDomain');

Route::group(['prefix' => 'v1/public'], static function () {
    Route::get(
        '/sessions/{session}',
        'SessionController@publicSession'
    )->name('public.session');

    Route::get(
        '/sessions/{session}/space-image',
        'SessionController@publicSessionSpaceImage'
    )->name('public.session-space-image');

    Route::get(
        'sessions/{sessionId}/session-areas',
        'SessionController@publicSessionAreas'
    )->name('public.session-areas');

    Route::get(
        'shows/{show}/sessions/{session}/areas/{area}/sectors',
        'ShowController@publicSessionAreaSectors'
    )->name('public.session-area-sectors');

    Route::get(
        'sessions/{sessionId}/session-sectors/{sessionSectorId}/free-seats',
        'SessionController@publicSectorFreeSeats'
    )->name('public.session-sectors-free-seats');

    Route::get(
        'session-sectors/{sessionSectorId}/session-seats',
        'SessionController@publicSessionSectorSeats'
    )->name('public.session-sectors-seats');

});

Route::group(['prefix' => 'v1', 'middleware' => ['jwt.refresh']], static function () {
    // Redsys POST
    Route::post('clients/{client}/orders/amount/{amount}', 'OrderController@redsys');

    SavitarRouter::resourceDataGrid(Place::class, static function () {
        SavitarRouter::resourceDataGrid(Door::class);

        SavitarRouter::resourceDataGrid(Space::class, static function () {
            Route::get('sessions', 'SessionController@index')->name('sessions-place-index');

            SavitarRouter::resourceDataGrid(Area::class, static function () {
                SavitarRouter::resourceDataGrid(Sector::class, static function () {
                    Route::delete('seats', 'SectorController@deleteSeats')->name('seats-destroy');
                });
            });
        });
    }, static function () {
    });

    SavitarRouter::resourceDataGrid(Enterprise::class);

    SavitarRouter::resourceDataGrid(Partner::class);

    SavitarRouter::resourceDataGrid(Artist::class, null, static function () {
    });

    Route::name('sessions.')->group(static function () {
        Route::prefix('sessions')->group(static function () {
            Route::get('', 'SessionController@index')->name('index');
            Route::prefix('{session}')->group(static function () {
                Route::get('', 'SessionController@show')->name('session-show');
                Route::get('orders/data-grid', 'OrderController@sessionOrdersDataGrid')->name('session-orders-data-grid');
                Route::get('orders/{type}/data-grid', 'OrderController@sessionTypeOrdersDataGrid')->name('session-type-orders-data-grid');
                // Invitations
                Route::get('invitations/data-grid', 'TicketController@sessionInvitationsDataGrid')->name('session-invitations-data-grid');  // Invitation Session Seats
                Route::get('print-model', 'SessionController@printModel')->name('get-print-model');
                Route::post('print-model', 'SessionController@updatePrintModel')->name('update-print-model');

                // doors
                Route::get('session-doors', 'SessionController@sessionDoors')->name('get-session-doors');

                // Access Control
                Route::name('accesses.')->group(static function () {
                    Route::prefix('accesses')->group(static function () {
                        Route::get('data-grid', 'AccessController@dataGrid')->name('data-grid');
                        Route::get('successful-check-count', 'AccessController@successfulAccessesCheckCount')->name('successful-accesses-count');
                        Route::get('', 'AccessController@index')->name('index');
                        Route::post('register/{sessionDoor}/{locator}', 'AccessController@register')->name('access-ticket-register');
                        Route::post('register-out/{sessionDoor}/{locator}', 'AccessController@registerOut')->name('access-ticket-register-out');
                        Route::prefix('{access}')->group(static function () {
                            Route::get('', 'AccessController@show')->name('show');
                            Route::patch('', 'AccessController@update')->name('update');
                            Route::delete('', 'AccessController@destroy')->name('destroy');
                        });
                    });
                });

                Route::get(
                    'session-sector-seats-info/{sessionSectorId}',
                    'SessionController@sessionSectorSoldSeatsInfo')
                    ->name('session-sector-seats-info');

                // Not numbered seats
                Route::prefix('session-sectors-seats/{sessionSector}')->group(static function() {
                    Route::get(
                        'not-numbered-seats',
                        'SessionSeatController@notNumberedSeatsBySessionSector')
                        ->name('session-sector-not-numbered-seats');

                    Route::post(
                        'lock-not-numbered-seats',
                        'SessionSeatController@lockNotNumberedSeats')
                        ->name('lock-not-numbered-seats');

                    Route::post(
                        'unlock-not-numbered-seats',
                        'SessionSeatController@unlockNotNumberedSeats')
                        ->name('unlock-not-numbered-seats');
                });

                // Purchase report
                Route::get('purchase-report', 'SessionController@purchaseReport')->name('session-purchase-report');
                Route::get('purchase-report/download', 'SessionController@downloadPurchaseReport')->name('download-session-purchase-report');
            });
        });
    });

    // Lock/Unlock Session Seats
    Route::patch('free-locked-session-seats', 'SessionSeatController@freeLockedSessionSeats')->name('session-free-locked-session-seats');
    Route::patch('lock-session-seats', 'SessionSeatController@lockSessionSeats')->name('lock-session-seats');

    // Delete/Restore Session Seats
    Route::patch('delete-session-seats', 'SessionSeatController@deleteSessionSeats')->name('delete-session-seats');
    Route::post('restore-session-seats', 'SessionSeatController@restoreSessionSeats')->name('restore-session-seats');

    SavitarRouter::resourceDataGrid(Show::class, static function () {
        SavitarRouter::resourceDataGrid(Session::class, static function () {
            Route::delete('fares/{fare}', 'FareController@destroy')->name('fare-destroy');
            Route::get('orders/data-grid', 'OrderController@showSessionOrders')->name('session-orders-data-grid');
            Route::get('orders/{type}/data-grid', 'OrderController@showSessionTypeOrders')->name('session-type-orders-data-grid');
            Route::post('orders/hard-ticket', 'OrderController@hardTicketOrder')->name('hard-ticket-create');
            Route::post('orders/invitation', 'OrderController@invitationOrder')->name('invitation-create');
            Route::post('orders/hard-ticket/manual', 'OrderController@hardTicketManualOrder')->name('hasrd-ticket-manual');
            Route::post('orders/check-prices-for-hard-ticket', 'OrderController@checkPricesForHardTicket')->name('check-prices-for-hard-ticket');
            Route::get('session-counts', 'SessionController@sessionCounts')->name('session-counts');
        }, static function () {
            Route::get('custom/data-grid', 'SessionController@customDataGrid')->name('custom-data-grid');
        });
    }, static function () {
    });

    SavitarRouter::resourceDataGrid(Discount::class, null, static function () {
        Route::get('check/{discountCode}', 'DiscountController@check')->name('check-discount');
    });

    SavitarRouter::resourceDataGrid(ShowClassification::class);

    SavitarRouter::resourceDataGrid(ShowTemplate::class);

    SavitarRouter::resourceDataGrid(ShowCategory::class);

    SavitarRouter::resourceDataGrid(Fare::class, null, static function () {
    });

    SavitarRouter::resourceDataGrid(Ticket::class, static function () {
        Route::get('download', 'TicketController@downloadTicket')->name('download-ticket');
    }, static function () {
        Route::get('invitations/data-grid', 'TicketController@invitationsDataGrid')->name('invitations-data-grid');
    });

    SavitarRouter::resourceDataGrid(Order::class, static function () {
        Route::get('tickets', 'OrderController@downloadTickets')->name('download-order-tickets');
        // Route::get('tickets/{ticket}', 'TicketController@downloadTicket')->name('download-ticket');
        Route::get('tickets/data-grid', 'TicketController@orderTicketsDataGrid')->name('order-tickets-data-grid');
        Route::get('payment-attempts/data-grid', 'PaymentAttemptController@dataGrid')->name('payment-attempts-data-grid');
    }, static function () {
        Route::get('{type}/data-grid', 'OrderController@typeDataGrid')->name('order-type-data-grid');
    });

    // Order returns
    SavitarRouter::resourceDataGrid(OrderReturnReason::class);
    SavitarRouter::resourceDataGrid(OrderReturn::class, static function () {
        Route::get('tickets/data-grid', 'TicketController@orderReturnTicketsDataGrid')->name('order-return-tickets-data-grid');
    }, static function () {
        Route::post('tpv', 'OrderReturnController@redsysStore')->name('order-return-tpv-data-grid');
    });

    SavitarRouter::resourceDataGrid(TicketSeasonGroup::class);
    SavitarRouter::resourceDataGrid(TicketSeason::class, static function () {
        Route::get('print-model', 'TicketSeasonController@printModel')->name('get-print-model');
        Route::post('print-model', 'TicketSeasonController@updatePrintModel')->name('update-print-model');

        Route::delete('fares/{fare}', 'FareController@destroy')->name('fare-destroy');
    }, static function () {
    });

    SavitarRouter::resourceDataGrid(TicketVoucher::class, static function () {
    }, static function () {
    });

    SavitarRouter::resourceDataGrid(Brand::class, static function () {
    }, static function () {
    });

    SavitarRouter::resourceDataGrid(Client::class, static function () {
        Route::name('orders.')->group(static function () {
            Route::prefix('orders')->group(static function () {
                Route::get('data-grid', 'OrderController@dataGrid')->name('data-grid');
                Route::get('', 'OrderController@index')->name('index');
                Route::get('check-redsys/{date}', 'OrderController@checkRedsys')->name('check-redsys');
                Route::prefix('{order}')->group(static function () {
                    Route::get('', 'OrderController@show')->name('show');
                    Route::get('invoice', 'OrderController@downloadInvoice')->name('download-invoice');
                });
            });
        });
        Route::name('order-returns.')->group(static function () {
            Route::prefix('order-returns')->group(static function () {
                Route::get('data-grid', 'OrderReturnController@dataGrid')->name('data-grid');
                Route::get('', 'OrderReturnController@index')->name('index');
                Route::prefix('{order-return}')->group(static function () {
                    Route::get('', 'OrderReturnController@show')->name('show');
                });
            });
        });
        Route::get('profile', 'ClientController@profile')->name('profile');
        Route::patch('profile', 'ClientController@updateProfile')->name('update-profile');
        Route::delete('apply-for-delete', 'ClientController@applyForDelete')->name('apply-for-delete');
    }, static function () {
        Route::get('active/data-grid', 'ClientController@activeDataGrid')->name('active-data-grid');
    });

    SavitarRouter::resourceDataGrid(PointOfSaleUser::class);

    Route::name('points-of-sale.')->group(static function () {
        Route::prefix('points-of-sale')->group(static function () {
            Route::get('data-grid', 'PointOfSaleController@dataGrid')->name('data-grid');
            Route::get('', 'PointOfSaleController@index')->name('index');
            Route::get('ticket-offices', 'PointOfSaleController@ticketOfficeIndex')->name('ticket-office-index');
            Route::post('', 'PointOfSaleController@store')->name('store');
            Route::get('count', 'PointOfSaleController@count')->name('count');
            Route::delete('', 'PointOfSaleController@destroyMultiple')->name('destroy-multiple');
            Route::post('duplicate', 'PointOfSaleController@duplicateMultiple')->name('duplicate-multiple');

            Route::prefix('{pointOfSale}')->group(static function () {
                Route::get('', 'PointOfSaleController@show')->name('show');
                Route::patch('', 'PointOfSaleController@update')->name('update');
                Route::delete('', 'PointOfSaleController@destroy')->name('destroy');
                Route::post('duplicate', 'PointOfSaleController@duplicate')->name('duplicate');
                Route::patch('restore', 'PointOfSaleController@restore')->name('restore');

                Route::get('users/data-grid', 'PointOfSaleUserController@dataGrid')->name('points-of-sale-users-data-grid');
                Route::get('orders/data-grid', 'OrderController@pointOfSaleOrdersDataGrid')->name('points-of-sale-orders-data-grid');
                Route::get('orders/{type}/data-grid', 'OrderController@pointOfSaleTypeOrdersDataGrid')->name('points-of-sale-type-orders-data-grid');
                Route::get('order-returns/data-grid', 'OrderReturnController@pointOfSaleOrderReturnsDataGrid')->name('points-of-sale-order-returns-data-grid');
                Route::get('orders/search-by-locator/{locator}', 'OrderController@pointOfSaleFindByLocator')->name('order-pv-find-by-locator');
                Route::get('orders/search-by-locator-or-nif/{locatorOrNif}', 'OrderController@pointOfSaleFindByLocatorOrNif')->name('order-pv-find-by-locator-or-nif');
                Route::post('orders/amount/{amount}', 'OrderController@pointOfSaleStoreOrder')->name('points-of-sale-store-order');
                Route::get('shows', 'ShowController@pointOfSaleIndex')->name('show-index');
                Route::get('shows/{show}/sessions', 'SessionController@pointOfSaleShowIndex')->name('show-session-index');
                Route::get('sessions', 'SessionController@pointOfSaleIndex')->name('sessions-index');
                Route::get('sessions/{session}/orders/data-grid', 'OrderController@pointOfSaleSessionOrdersDataGrid')->name('points-of-sale-sessions-orders-data-grid');
                Route::get('sessions/{session}/orders/{type}/data-grid', 'OrderController@pointOfSaleSessionTypeOrdersDataGrid')->name('points-of-sale-type-sessions-orders-data-grid');
                Route::get('discounts', 'DiscountController@pointOfSaleIndex')->name('discounts-index');
                Route::get('discounts/data-grid', 'DiscountController@pointOfSaleDataGrid')->name('discounts-data-grid');
                // Checks if ticketOffice
                Route::get('check-if-ticket-office/{place}', 'PointOfSaleController@checkIfTicketOffice')->name('check-ticket-office');
                Route::get('check-if-ticket-office/{place}/sessions/{session}', 'PointOfSaleController@checkIfTicketOfficeForSession')->name('check-ticket-office-session');
                // Register new client
                Route::post('register-client', 'UserController@registerClient')->name('points-of-sale-register-client');

                //fares
                Route::get('fare/{fare}/ticket-count', 'FareController@ticketCountByFarePointOfSale')->name('fare-point-of-sale-ticket-count');
            });
        });
    });

    Route::name('accounts.')->group(static function () {
        Route::prefix('accounts')->group(static function () {
            Route::get('promoter/data-grid', 'AccountController@dataGrid')->name('promoter-data-grid');
            Route::post('register', 'AccountController@register')->name('register');
            Route::prefix('{account}')->group(static function () {
                Route::get('special', 'AccountController@show')->name('special');
                Route::patch('special', 'AccountController@update')->name('special-update');
                Route::name('users.')->group(static function () {
                    Route::prefix('users')->group(static function () {
                        Route::prefix('{user}')->group(static function () {
//                                Route::get('full-details', 'UserController@fullDetails')->name('full-details');
//                                Route::get('change-language/{language}', 'UserController@changeLanguage')->name('change-language');

                            SavitarRouter::resourceDataGrid(Show::class);
                        });
                    });
                });
                Route::name('shows.')->group(static function () {
                    Route::prefix('shows')->group(static function () {
                        Route::get('data-grid', 'ShowController@dataGrid')->name('data-grid');
                    });
                });
                Route::name('orders.')->group(static function () {
                    Route::prefix('orders')->group(static function () {
                        Route::get('data-grid', 'OrderController@dataGrid')->name('data-grid');
                    });
                });
                Route::name('sessions.')->group(static function () {
                    Route::prefix('sessions')->group(static function () {
                        Route::get('', 'SessionController@accountIndex')->name('account-session-index');

                        Route::prefix('{session}')->group(static function () {
                            Route::get('orders/data-grid', 'OrderController@accountSessionOrdersDataGrid')->name('account-session-orders-data-grid');
                            Route::get('orders/{type}/data-grid', 'OrderController@accountSessionTypeOrdersDataGrid')->name('account-session-type-orders-data-grid');
                        });
                    });
                });

                SavitarRouter::resourceDataGrid(Order::class, static function () {
                    Route::get('tickets', 'OrderController@downloadTickets')->name('download-order-tickets');
                    // Route::get('tickets/{ticket}', 'TicketController@downloadTicket')->name('download-ticket');
                    Route::get('tickets/data-grid', 'TicketController@orderTicketsDataGrid')->name('order-tickets-data-grid');
                    Route::get('payment-attempts/data-grid', 'PaymentAttemptController@dataGrid')->name('payment-attempts-data-grid');
                }, static function () {
                    Route::get('{type}/data-grid', 'OrderController@typeDataGrid')->name('order-type-data-grid');
                });
            });
        });
    });

    Route::name('consultations.')->group(static function () {
        Route::prefix('consultations')->group(static function () {
            Route::get('data-grid', 'ConsultationController@dataGrid')->name('data-grid');
            Route::get('', 'ConsultationController@index')->name('index');
            Route::delete('', 'ConsultationController@destroyMultiple')->name('destroy-multiple');

            Route::prefix('{consultation}')->group(static function () {
                Route::get('', 'ConsultationController@show')->name('show');
                Route::patch('', 'ConsultationController@update')->name('update');
                Route::delete('', 'ConsultationController@destroy')->name('destroy');
            });
        });
    });

    // Admins
    Route::get('users/admins/data-grid', 'UserController@adminsDataGrid')->name('admins-users-data-grid');
    Route::get('roles/{savitarRole}/admins/data-grid', 'UserController@adminsDataGrid')->name('admins-data-grid');

    // Access control
    Route::get('shows-access-index', 'ShowController@accessIndex')->name('shows.access-index');
    Route::get('shows-access-history-index', 'ShowController@accessHistoryIndex')->name('shows.access-history-index');
    Route::get('users/access/data-grid', 'UserController@accessUsersDataGrid')->name('access-users-data-grid');
    Route::post('users/access', 'UserController@storeAccessUser')->name('store-access-user');
});
