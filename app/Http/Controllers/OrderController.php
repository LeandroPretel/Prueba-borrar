<?php

namespace App\Http\Controllers;

use App\Account;
use App\Client;
use App\Discount;
use App\InvoiceNumber;
use App\Order;
use App\PaymentAttempt;
use App\PointOfSale;
use App\Session;
use App\SessionAreaFare;
use App\SessionSeat;
use App\Show;
use App\SimpleMailTemplate;
use App\Ticket;
use Carbon\Carbon;
use DNS1D;
use Exception;
use JWTAuth;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Sermepa\Tpv\Tpv;
use Sermepa\Tpv\TpvException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class OrderController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Order::class);
        $this->configureCRUD([
            'modelClass' => Order::class,
            'indexAppends' => [
                // 'show',
                // 'session.showTemplate',
                // 'place',
                'tickets.session.show',
                'tickets.session.showTemplate',
                'tickets.session.place.province',
                'ticketSeasonOrder.ticketSeason',
                'ticketVoucherOrder.ticketVoucher',
            ],
            'indexConditions' => [
                ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ],
            'showAppends' => [
                // 'shows',
                // 'sessions.showTemplate',
                // 'places',
                'tickets.session.show',
                'tickets.session.showTemplate',
                'tickets.session.place.province',
                'tickets.sessionSeat.sessionSector',
                'client.user',
                'pointOfSale',
                'ticketSeasonOrder.ticketSeason',
                'ticketVoucherOrder.ticketVoucher',
                'paymentAttempts',
            ],
            'defaultOrderBy' => 'createdAt',
            'defaultSortOrder' => 'desc',
        ]);
        $this->configureDataGrid([
            'modelClass' => Order::class,
            'dataGridTitle' => 'Compras',
            'defaultOrderBy' => 'createdAt',
            'defaultSortOrder' => 'desc',
            'dataGridConditions' => [
                ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
                ['column' => 'Ticket.orderReturnId', 'operator' => '=', 'value' => null, 'relationShip' => 'tickets'],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param $type
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function typeDataGrid(Request $request, $type)
    {
        $headers = $this->dataGridHeaders;
        $headers['type']['default'] = false;
        $this->setDataGridHeaders($headers);
        $dataGridConditions = [
            ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ['column' => 'Order.type', 'operator' => '=', 'value' => $type],
        ];
        $this->setDataGridConditions($dataGridConditions);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param Show $show
     * @param Session $session
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function showSessionOrders(Request $request, Show $show, Session $session)
    {
        return $this->showSessionTypeOrders($request, $show, $session);
    }

    /**
     * @param Request $request
     * @param Show $show
     * @param Session $session
     * @param null $type
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function showSessionTypeOrders(Request $request, Show $show, Session $session, $type = null)
    {
        $dataGridConditions = [
            ['column' => 'Ticket.sessionId', 'operator' => '=', 'value' => $session->id, 'relationShip' => 'tickets'],
            ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ['column' => 'orderReturnId', 'operator' => '=', 'value' => null, 'relationShip' => 'tickets'],
        ];
        if ($type) {
            $dataGridConditions[] = [
                'column' => 'Order.type', 'operator' => '=', 'value' => $type,
            ];
            $headers = $this->dataGridHeaders;
            $headers['type']['default'] = false;
            $this->setDataGridHeaders($headers);
        }
        $this->setDataGridConditions($dataGridConditions);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function sessionOrdersDataGrid(Request $request, Session $session)
    {
        return $this->sessionTypeOrdersDataGrid($request, $session);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @param null $type
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function sessionTypeOrdersDataGrid(Request $request, Session $session, $type = null)
    {
        $dataGridConditions = [
            ['column' => 'Ticket.sessionId', 'operator' => '=', 'value' => $session->id, 'relationShip' => 'tickets'],
            ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ['column' => 'Ticket.orderReturnId', 'operator' => '=', 'value' => null, 'relationShip' => 'tickets'],
        ];
        if ($type) {
            $dataGridConditions[] = [
                'column' => 'Order.type', 'operator' => '=', 'value' => $type,
            ];
            $headers = $this->dataGridHeaders;
            $headers['type']['default'] = false;
            $this->setDataGridHeaders($headers);
        }
        $this->setDataGridConditions($dataGridConditions);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param Account $account
     * @param Session $session
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function accountSessionOrdersDataGrid(Request $request, Account $account, Session $session)
    {
        return $this->accountSessionTypeOrdersDataGrid($request, $account, $session);
    }

    /**
     * @param Request $request
     * @param Account $account
     * @param Session $session
     * @param null $type
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function accountSessionTypeOrdersDataGrid(Request $request, Account $account, Session $session, $type = null)
    {
        $dataGridConditions = [
            ['column' => 'Order.accountId', 'operator' => '=', 'value' => $account->id],
            ['column' => 'Ticket.sessionId', 'operator' => '=', 'value' => $session->id, 'relationShip' => 'tickets'],
            ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ['column' => 'Ticket.orderReturnId', 'operator' => '=', 'value' => null, 'relationShip' => 'tickets'],
        ];
        if ($type) {
            $dataGridConditions[] = [
                'column' => 'Order.type', 'operator' => '=', 'value' => $type,
            ];
            $headers = $this->dataGridHeaders;
            $headers['type']['default'] = false;
            $this->setDataGridHeaders($headers);
        }
        $this->setDataGridConditions($dataGridConditions);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param Session $session
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleSessionOrdersDataGrid(Request $request, PointOfSale $pointOfSale, Session $session)
    {
        return $this->pointOfSaleSessionTypeOrdersDataGrid($request, $pointOfSale, $session);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param Session $session
     * @param null $type
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleSessionTypeOrdersDataGrid(Request $request, PointOfSale $pointOfSale, Session $session, $type = null)
    {
        $dataGridConditions = [
            ['column' => 'Order.pointOfSaleId', 'operator' => '=', 'value' => $pointOfSale->id],
            ['column' => 'Ticket.sessionId', 'operator' => '=', 'value' => $session->id, 'relationShip' => 'tickets'],
            ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ['column' => 'Ticket.orderReturnId', 'operator' => '=', 'value' => null, 'relationShip' => 'tickets'],
        ];
        if ($type) {
            $dataGridConditions[] = [
                'column' => 'Order.type', 'operator' => '=', 'value' => $type,
            ];
            $headers = $this->dataGridHeaders;
            $headers['type']['default'] = false;
            $this->setDataGridHeaders($headers);
        }
        $this->setDataGridConditions($dataGridConditions);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleOrdersDataGrid(Request $request, PointOfSale $pointOfSale)
    {
        return $this->pointOfSaleTypeOrdersDataGrid($request, $pointOfSale);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param null $type
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleTypeOrdersDataGrid(Request $request, PointOfSale $pointOfSale, $type = null)
    {
        // goodTicketsCount
        $defaultColumns = ['locator', 'via', 'status', 'amount', 'createdAt'];
        $dataGridConditions = [
            ['column' => 'Order.pointOfSaleId', 'operator' => '=', 'value' => $pointOfSale->id],
            ['column' => 'Order.status', 'operator' => '<>', 'value' => 'pending'],
            ['column' => 'orderReturnId', 'operator' => '=', 'value' => null, 'relationShip' => 'tickets'],
        ];
        if ($type) {
            $dataGridConditions[] = [
                'column' => 'Order.type', 'operator' => '=', 'value' => $type,
            ];
        } else {
            $defaultColumns[] = 'type';
        }
        $this->setDataGridConditions($dataGridConditions);
        // 'userName',
        $this->setDefaultColumns($defaultColumns);
        return $this->dataGrid($request);
    }

    /**
     * Stores a new redsys charge
     *
     * @param Request $request
     * @param Client $client
     * @param $amount
     * @return void|array
     * @throws Exception
     */
    public function redsys(Request $request, Client $client, $amount)
    {
        /**
         * Tests:
         * 348593583
         * sq7HjrUOBfKmC576ILgskD5srU870gJ7
         */
        // Create the order, to create the payment intent and the tickets
        $show = Show::findOrFail($request->input('showId'));
        $order = new Order();
        $pointOfSale = PointOfSale::where('slug', 'redentradas')->first();
        if ($pointOfSale) {
            $order->pointOfSale()->associate($pointOfSale);
        }
        $order->account()->associate($show->accountId);
        $order->client()->associate($client);
        if ($request->input('pickUpMethod') === 'home-ticket') {
            $order->type = 'home-ticket';
        }
        // Type
        $order->amount = $amount;
        // $order->attemptDate = Carbon::now();
        // $order->redsysNumber = time();
        if ($request->input('discountId')) {
            $discount = Discount::findOrFail($request->input('discountId'));
            $discountController = new DiscountController();
            $discountUsable = $discountController->useDiscount($discount);
            if ($discountUsable) {
                $order->discount()->associate($discount);
            }
            // El descuento aplicado no es usable, hay que revertirlo ELSE
        }
        // TODO da error pone pointOfSale id null
        $order->save();

        $paymentAttempt = new PaymentAttempt();
        $paymentAttempt->order()->associate($order);
        $paymentAttempt->amount = $amount;
        $paymentAttempt->redsysNumber = time();
        $paymentAttempt->paymentMethod = 'card';
        $paymentAttempt->save();

        // Creamos las entradas
        foreach ($request->input('tickets') as $ticketInfo) {
            $ticket = new Ticket();
            $ticket->session()->associate($request->input('sessionId'));
            $ticket->order()->associate($order);
            if ($ticketInfo['sessionSeatId']) {
                $ticket->sessionSeat()->associate($ticketInfo['sessionSeatId']);
            } // The sessionSector is not numbered, take the first free sessionSeat for the ticket
            else {
                $ticket->sessionSeat()->associate(SessionSeat::where('status', 'enabled')
                    ->where('sessionId', $request->input('sessionId'))
                    ->where('sessionSectorId', $ticketInfo['sessionSectorId'])->first());
            }
            if ($ticketInfo['sessionAreaFareId']) {
                $ticket->sessionAreaFare()->associate($ticketInfo['sessionAreaFareId']);
            }
            $ticket->baseAmount = $ticketInfo['baseAmount'];
            $ticket->distributionAmount = $ticketInfo['distributionAmount'];
            $ticket->amount = $ticketInfo['amount'];

            if (isset($discountUsable) && $discountUsable) {
                $ticket->baseAmountWithDiscount = $ticketInfo['baseAmountWithDiscount'];
                $ticket->distributionAmountWithDiscount = $ticketInfo['distributionAmountWithDiscount'];
                $ticket->amountWithDiscount = $ticketInfo['amountWithDiscount'];
            }
            $ticket->save();
        }

        if ($request->query('titular')) {
            $titular = $request->query('titular');
        } else {
            $titular = $client->name ?: $client->user->email;
        }
        try {
            $redsys = new Tpv();
            $key = config('redsys.key');
            // $redsys->setEnvironment('live'); //Entorno produccion
            $redsys->setEnvironment('test'); //Entorno pruebas
            $redsys->setUrlOk(config('redsys.api_url') . '/api/v1/rok?t=false&paymentAttemptId=' . encrypt($paymentAttempt->id)); //Url OK
            $redsys->setUrlKo(config('redsys.api_url') . '/api/v1/rerr?t=false&paymentAttemptId=' . encrypt($paymentAttempt->id)); //Url KO
            $redsys->setNotification(config('redsys.api_url') . '/api/v1/rnotification?t=false&paymentAttemptId=' . encrypt($paymentAttempt->id)); //Url de notificacion

            $redsys->setAmount($amount);
            $redsys->setOrder($paymentAttempt->redsysNumber);
            $redsys->setMerchantcode(config('redsys.merchant_code')); //Reemplazar por el código que proporciona el banco
            $redsys->setCurrency('978');
            $redsys->setTransactiontype('0');
            $redsys->setTerminal('1');
            // $redsys->setTerminal('1');
            $redsys->setMethod('C'); //Solo pago con tarjeta, no mostramos iupay
            $redsys->setVersion('HMAC_SHA256_V1');
            $redsys->setTradeName('Redentradas');
            $redsys->setTitular($titular);
            $redsys->setProductDescription('Compra de entradas');
            $redsys->setAttributesSubmit('btn_submit', 'btn_id', 'Enviar', 'display:none');
            $signature = $redsys->generateMerchantSignature($key);
            $redsys->setMerchantSignature($signature);

            // $redsys->setPan('4548812049400004'); //Número de la tarjeta
            // $redsys->setExpiryDate('2012'); //AAMM (año y mes)
            // $redsys->setCVV2('123'); //CVV2 de la tarjeta
            // return $redsys->getParameters();
            // $form = $redsys->createForm();
            $form = $redsys->executeRedirection();
            echo $form;
        } catch (TpvException $tpvException) {
            Log::error($tpvException->getMessage());
            echo $tpvException->getMessage();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            echo $exception->getMessage();
        }
    }

    /**
     * Redsys ok function
     *
     * @param Request $request
     * @return Factory|View
     * @throws Exception
     */
    public function redsysOk(Request $request)
    {
        $test = $request->query('t');
        $authorizationCode = null;
        $paymentAttempt = PaymentAttempt::find(decrypt($request->query('paymentAttemptId')));

        if ($request->query('Ds_MerchantParameters')) {
            $decoded = json_decode(base64_decode($request->query('Ds_MerchantParameters')), false);
            if ($decoded) {
                $authorizationCode = $decoded->Ds_AuthorisationCode;
            }
        }
        // Logging
        Log::info('Redsys ha detectado un pago correcto.');

        if ($test === 'false' || !$test) {
            $order = clone($paymentAttempt->order);
            $order->loadMissing([
                'account.enterprises',
                'client',
                'tickets.session.show',
                'tickets.session.showTemplate',
                'tickets.session.sessionPrintModel',
                'tickets.session.place.province',
                'tickets.sessionSeat.sessionSector.sessionArea.space',
            ]);
            // Generates qrcode and barcode for the order locator
            $order['barcode'] = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->locator, 'C128') . '" alt="barcode"/>';
            $order['qrcode'] = QrCode::format('png')->size(120)->margin(0)->generate($order->locator);
            // Generates qrcode and barcode for each ticket locator
            foreach ($order->tickets as $ticket) {
                $ticket['barcode'] = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($ticket->locator, 'C128') . '" alt="barcode"/>';
                $ticket['qrcode'] = QrCode::format('png')->size(120)->margin(0)->generate($ticket->locator);
            }
            // $qrcode = '<img src="data:image/png;base64,' . QrCode::size(200)->generate($order->id) . '" alt="qrcode"   />';

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('orders.tickets', ['order' => $order]);

            \Mail::to('rubenaguilerabarzaga@gmail.com')->send(
                new SimpleMailTemplate(
                    'Resumen de compra',
                    'Compra finalizada correctamente',
                    $pdf->output(),
                    [
                        'Las entradas han sido adjuntadas a este correo'
                    ],
                    null,
                    null,
                    null,
                    null
                )
            );

            $this->updateAttempt($paymentAttempt, 'successful', $authorizationCode);
        }

        return view('redsys.ok');
    }

    /**
     * Redsys error function
     *
     * @param Request $request
     * @return Factory|View
     * @throws Exception
     */
    public function redsysError(Request $request)
    {
        $test = $request->query('t');
        $authorizationCode = null;
        $paymentAttempt = PaymentAttempt::find(decrypt($request->query('paymentAttemptId')));

        // Logging
        Log::info('Redsys ha detectado un pago incorrecto.');
        if ($request->query('Ds_MerchantParameters')) {
            $decoded = json_decode(base64_decode($request->query('Ds_MerchantParameters')), false);
            if ($decoded) {
                $authorizationCode = $decoded->Ds_AuthorisationCode;
                Log::info(json_encode($decoded));
            }
        }
        if ($test === 'false' || !$test) {
            $this->updateAttempt($paymentAttempt, 'failed', $authorizationCode);
        }
        return view('redsys.error');
    }

    /**
     * Redsys notification function
     *
     * @param Request $request
     * @return Factory|View|int
     * @throws Exception
     */
    public function redsysNotification(Request $request)
    {
        // $test = $request->query('t');
        $authorizationCode = null;
        try {
            $redsys = new Tpv();
            $key = config('redsys.key');
            $parameters = $redsys->getMerchantParameters($_POST['Ds_MerchantParameters']);
            $DsResponse = $parameters['Ds_Response'];
            $DsResponse += 0;
            $authorizationCode = null;
            if ($parameters['Ds_AuthorisationCode']) {
                $authorizationCode = $parameters['Ds_AuthorisationCode'];
                $request['authorizationCode'] = $authorizationCode;
            }
            $paymentAttempt = PaymentAttempt::find(decrypt($request->query('paymentAttemptId')));
            if ($DsResponse <= 99 && $redsys->check($key, $_POST)) {
                // Logging
                Log::info('Notificación redsys, pago correcto (' . $authorizationCode . ')');
                // Acciones a realizar si es correcto, por ejemplo validar una reserva, mandar un mail de OK, guardar en bbdd o contactar con mensajería para preparar un pedido
                if ($paymentAttempt && $paymentAttempt->status === 'successful') {
                    if (isset($authorizationCode)) {
                        $paymentAttempt->authorizationCode = $authorizationCode;
                        $paymentAttempt->save();
                    }
                } else {
                    $this->updateAttempt($paymentAttempt, 'successful', $authorizationCode);
                }
                return view('redsys.ok');
            }
            // Logging
            Log::info('Notificación redsys, pago incorrecto (' . $authorizationCode . ')');
            // Acciones a realizar si ha sido erroneo
            if ($paymentAttempt) {
                if ($paymentAttempt->status === 'successful') {
                    $paymentAttempt->status = 'failed';
                }
                if (isset($authorizationCode)) {
                    $paymentAttempt->authorizationCode = $authorizationCode;
                }
                $paymentAttempt->save();
            } else {
                $this->updateAttempt($paymentAttempt, 'failed', $authorizationCode);
            }
            return view('redsys.error');
        } catch (TpvException $tpvException) {
            Log::error($tpvException->getMessage());
            echo $tpvException->getMessage();
        }
        return 200;
    }

    /**
     * Save payment function
     *
     * @param PaymentAttempt $paymentAttempt
     * @param string $status
     * @param null $authorizationCode
     * @return Order|Model|null
     * @throws Exception
     */
    public function updateAttempt(PaymentAttempt $paymentAttempt, $status = 'successful', $authorizationCode = null)
    {
        $token = JWTAuth::getToken();
        $current = '';
        if ($token) {
            JWTAuth::setToken($token);
            $payload = JWTAuth::getPayload();
            $payload['impersonated'] ? $current = 'superadmin@redentradas.es' : $current = $payload['email'];
            if (!$paymentAttempt->createdBy) {
                $paymentAttempt->createdBy = $current;
            }
            $paymentAttempt->updatedBy = $current;
        }
        $paymentAttempt->status = $status;
        if ($authorizationCode) {
            $paymentAttempt->authorizationCode = $authorizationCode;
        }
        $paymentAttempt->save();
        // If the order is successful, sell the sessionSeats
        if ($paymentAttempt->status === 'successful') {
            // Update the sessionSeats to sold, mass update to optimize.
            $sessionSeatIds = Ticket::whereOrderId($paymentAttempt->orderId)->select('sessionSeatId')->get();
            SessionSeat::whereIn('id', $sessionSeatIds)->update([
                'status' => 'sold',
                'updatedAt' => Carbon::now(),
                'updatedBy' => $current
            ]);
        } // If not, ¿delete the tickets? TODO: Partially paid
        else {
            Ticket::whereOrderId($paymentAttempt->orderId)->delete();
        }
        // Logging
        Log::info('El usuario con el email ' . $paymentAttempt->updatedBy . ' ha registrado una compra (' . $paymentAttempt->status . ') de ' . $paymentAttempt->amount . ' euros.');

        /* Send Email with the order?
        if ($order->status === 'successful' && ($creatingOrder || $order->getOriginal()['status'] === 'failed')) {
            try {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('payments.client', ['order' => $order]);

                $message = new MailTemplate($order->updatedBy,
                    'Recarga de typis',
                    null,
                    ['Has registrado una recarga de ' . $order->amount . 'euros. Te adjuntamos la factura simplificada de la misma.'],
                    '',
                    '',
                    '',
                    '#4284C4', [], '', 'info@typis.es', 'typis.es');
                $message->attachData($pdf->output(), 'Recarga_' . $order->locator . '.pdf');

                Mail::to($order->updatedBy)->send($message);
            } catch (Exception $exception) {
                Log::info('Ha ocurrido un error al enviar el correo a ' . $order->updatedBy . ' con los datos de la recarga ' . $order->locator);
            }
        }
        */
        return $paymentAttempt;
    }

    /**
     * Checks the redsys order (Interval)
     *
     * @param Client $client
     * @param $date
     * @return JsonResponse
     */
    public function checkRedsys(Client $client, $date): JsonResponse
    {
        $date = Carbon::createFromTimestamp(strtotime($date));
        /** @var PaymentAttempt $paymentAttempt */
        $paymentAttempt = PaymentAttempt::leftJoin('Order', 'Order.id', '=', 'PaymentAttempt.orderId')
            ->where('PaymentAttempt.status', '<>', 'attempt')
            ->orderBy('PaymentAttempt.updatedAt', 'desc')
            ->select(['PaymentAttempt.*'])
            ->first();

        $check = false;
        if ($paymentAttempt && Carbon::createFromTimestamp(strtotime($paymentAttempt->updatedAt))->greaterThanOrEqualTo($date)
            && Carbon::createFromTimestamp(strtotime($paymentAttempt->createdAt))->greaterThanOrEqualTo($date)) {
            $paymentAttempt->status === 'successful' ? $check = true : $check = false;
            $exists = true;
        } else {
            $exists = false;
        }
        return response()->json([
            'check' => $check,
            'exists' => $exists,
        ]);
    }

    /**
     * Generate an unique invoice number
     *
     * @param Order $order
     * @throws Throwable
     */
    public function generateNumber(Order $order): void
    {
        DB::beginTransaction();
        //whereType('invoice')
        $invoiceNumber = InvoiceNumber::whereYearNumber($order->createdAt->year)->lockForUpdate()->first();
        if (!$invoiceNumber) {
            $invoiceNumber = new InvoiceNumber();
            // $invoiceNumber->type = 'invoice';
            $invoiceNumber->yearNumber = $order->createdAt->year;
            $invoiceNumber->number = 1;
            $invoiceNumber->save();
        }

        // $order->number = 'F' . $invoiceNumber->yearNumber . '-' . str_pad($invoiceNumber->number, 9, '0', STR_PAD_LEFT);

        $invoiceNumber->number++;
        $invoiceNumber->save();
        DB::commit();
    }

    /**
     * @param Client $client
     * @param Order $order
     * @return mixed
     */
    public function downloadInvoice(Client $client, Order $order)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('payments.client', ['order' => $order]);
        return $pdf->download('Factura-' . $order->locator . '.pdf');
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function downloadTickets(Order $order)
    {
        $order->loadMissing([
            'account.enterprises',
            'client',
            'tickets.session.show',
            'tickets.session.showTemplate',
            'tickets.session.sessionPrintModel',
            'tickets.session.place.province',
            'tickets.sessionSeat.sessionSector.sessionArea.space',
        ]);
        // Generates qrcode and barcode for the order locator
        $order['barcode'] = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->locator, 'C128') . '" alt="barcode"/>';
        $order['qrcode'] = QrCode::format('png')->size(120)->margin(0)->generate($order->locator);
        // Generates qrcode and barcode for each ticket locator
        foreach ($order->tickets as $ticket) {
            $ticket['barcode'] = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($ticket->locator, 'C128') . '" alt="barcode"/>';
            $ticket['qrcode'] = QrCode::format('png')->size(120)->margin(0)->generate($ticket->locator);
        }
        // $qrcode = '<img src="data:image/png;base64,' . QrCode::size(200)->generate($order->id) . '" alt="qrcode"   />';

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('orders.tickets', ['order' => $order]);

        // return view('orders.tickets', ['order' => $order]);

        return $pdf->download('Compra-' . $order->locator . '.pdf');
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @param $amount
     * @return JsonResponse
     * @throws Exception
     */
    public function pointOfSaleStoreOrder(Request $request, PointOfSale $pointOfSale, $amount): JsonResponse
    {
        // Create the order, to create the payment intent and the tickets
        $show = Show::findOrFail($request->input('showId'));
        $order = new Order();
        $order->pointOfSale()->associate($pointOfSale);
        $order->account()->associate($show->accountId);
        // $order->client()->associate($client);
        // $order->user()->associate($client->user);
        $order->amount = $amount;
        if ($request->input('discountId')) {
            $discount = Discount::findOrFail($request->input('discountId'));
            $discountController = new DiscountController();
            $discountUsable = $discountController->useDiscount($discount);
            if ($discountUsable) {
                $order->discount()->associate($discount);
            }
        }
        $order->save();

        $paymentAttempt = new PaymentAttempt();
        $paymentAttempt->order()->associate($order);
        $paymentAttempt->amount = $amount;
        $paymentAttempt->status = 'successful';
        if ($request->filled('paymentMethod')) {
            $paymentAttempt->paymentMethod = $request->input('paymentMethod');
        }
        $paymentAttempt->save();

        // Creamos las entradas
        foreach ($request->input('tickets') as $ticketInfo) {
            $ticket = new Ticket();
            $ticket->session()->associate($request->input('sessionId'));
            $ticket->order()->associate($order);
            if ($ticketInfo['sessionSeatId']) {
                $ticket->sessionSeat()->associate($ticketInfo['sessionSeatId']);
            }
            if ($ticketInfo['sessionAreaFareId']) {
                $ticket->sessionAreaFare()->associate($ticketInfo['sessionAreaFareId']);
            }
            $ticket->baseAmount = $ticketInfo['baseAmount'];
            $ticket->distributionAmount = $ticketInfo['distributionAmount'];
            $ticket->amount = $ticketInfo['amount'];
            if (isset($discountUsable) && $discountUsable) {
                $ticket->baseAmountWithDiscount = $ticketInfo['baseAmountWithDiscount'];
                $ticket->distributionAmountWithDiscount = $ticketInfo['distributionAmountWithDiscount'];
                $ticket->amountWithDiscount = $ticketInfo['amountWithDiscount'];
            }
            $ticket->save();
        }
        $payload = JWTAuth::parseToken()->getPayload();
        // Modify the sessionSeats to set the status to 'sold'
        $sessionSeatIds = $order->tickets()->select('sessionSeatId')->get();
        SessionSeat::whereIn('id', $sessionSeatIds)->update([
            'status' => 'sold',
            'updatedAt' => Carbon::now(),
            'updatedBy' => $payload['email']
        ]);

        return response()->json(['order' => $order]);
    }

    /**
     * @param Request $request
     * @param Show $show
     * @param Session $session
     * @return JsonResponse
     * @throws Exception
     */
    public function hardTicketOrder(Request $request, Show $show, Session $session): JsonResponse
    {
        // Create the order, to create the payment intent and the tickets
        $order = new Order();
        $order->pointOfSale()->associate(PointOfSale::whereSlug('web-redentradas')->first());
        $order->account()->associate($show->accountId);
        // $order->client()->associate($client);
        // $order->user()->associate($client->user);
        $order->type = 'hard-ticket';
        // Sum amount
        $amount = 0;
        foreach ($request->input('tickets') as $ticketInfo) {
            $amount += $ticketInfo['amountWithDiscount'];
        }
        $order->amount = $amount;
        $order->save();

        $paymentAttempt = new PaymentAttempt();
        $paymentAttempt->order()->associate($order);
        $paymentAttempt->amount = $amount;
        $paymentAttempt->status = 'successful';
        if ($request->filled('paymentMethod')) {
            $paymentAttempt->paymentMethod = $request->input('paymentMethod');
        }
        $paymentAttempt->save();

        // Creamos las entradas
        foreach ($request->input('tickets') as $ticketInfo) {
            $ticket = new Ticket();
            $ticket->order()->associate($order);
            $ticket->session()->associate($session);
            if ($ticketInfo['sessionSeatId']) {
                $ticket->sessionSeat()->associate($ticketInfo['sessionSeatId']);
            }
            if ($ticketInfo['sessionAreaFareId']) {
                $ticket->sessionAreaFare()->associate($ticketInfo['sessionAreaFareId']);
            }
            $ticket->baseAmount = $ticketInfo['baseAmount'];
            $ticket->baseAmountWithDiscount = $ticketInfo['baseAmountWithDiscount'];
            $ticket->distributionAmount = $ticketInfo['distributionAmount'];
            $ticket->distributionAmountWithDiscount = $ticketInfo['distributionAmountWithDiscount'];
            $ticket->amount = $ticketInfo['amount'];
            $ticket->amountWithDiscount = $ticketInfo['amountWithDiscount'];
            $ticket->save();
        }
        // Modify the sessionSeats to set the status to 'hard-ticket'
        $sessionSeatIds = $order->tickets()->select('sessionSeatId')->get();
        SessionSeat::whereIn('id', $sessionSeatIds)->update(['status' => 'hard-ticket', 'updatedAt' => Carbon::now()]);

        return response()->json(['order' => $order]);
    }

    /**
     * Invitation order.
     *
     * @param Request $request
     * @param Show $show
     * @param Session $session
     * @return JsonResponse
     * @throws Exception
     */
    public function invitationOrder(Request $request, Show $show, Session $session): JsonResponse
    {
        $order = new Order();
        $order->pointOfSale()->associate(PointOfSale::whereSlug('web-redentradas')->first());
        $order->account()->associate($show->accountId);
        $order->type = 'invitation';
        $order->amount = 0;
        $order->status = 'paid';
        $order->save();

        if ($request->has('sessionSeatIds')) {
            foreach ($request->input('sessionSeatIds') as $sessionSeatId) {
                $sessionSeat = SessionSeat::whereId($sessionSeatId)->where('status', 'enabled')->first();
                if ($sessionSeat) {
                    $ticket = new Ticket();
                    $ticket->order()->associate($order);
                    $ticket->sessionSeat()->associate($sessionSeat);
                    $ticket->session()->associate($session);
                    $ticket->save();
                }
            }

            $invitationsCount = SessionSeat::whereIn('id', $request->input('sessionSeatIds'))
                ->where('status', 'enabled')
                ->update(['status' => 'invitation', 'updatedAt' => Carbon::now()]);

            // Check if some sessionSeats couldn't be updated, cause someone bought/reserved while doing it
            $gapCount = count($request->input('sessionSeatIds')) - $invitationsCount;

            return response()->json(['invitationsCount' => $invitationsCount, 'gapCount' => $gapCount], 200);
        }
        return response()->json([], 400);
    }

    /**
     * @param Request $request
     * @param Show $show
     * @param Session $session
     * @return JsonResponse
     * @throws Exception
     */
    public function hardTicketManualOrder(Request $request, Show $show, Session $session): JsonResponse
    {
        if ($session->isActive && $request->has('sessionSeatIds')) {
            $order = new Order();
            $order->pointOfSale()->associate(PointOfSale::whereSlug('web-redentradas')->first());
            $order->account()->associate($show->accountId);
            // $order->client()->associate($client);
            $order->type = 'hard-ticket';
            // Sum amount
            $sessionSeats = collect();
            $amount = 0;
            foreach ($request->input('sessionSeatIds') as $sessionSeatId) {
                $sessionSeat = SessionSeat::whereId($sessionSeatId)
                    ->where(static function ($builder) {
                        $builder->where('status', 'enabled')
                            ->orWhere('status', 'locked');
                    })
                    ->with('sessionSector')
                    ->first();
                if ($sessionSeat) {
                    $sessionAreaFare = SessionAreaFare::whereSessionAreaId($sessionSeat->sessionSector->sessionAreaId)
                        ->whereFareId($session->defaultFareId)
                        ->first();
                    $sessionSeat['queuedAreaFare'] = $sessionAreaFare;
                    $sessionSeats->push($sessionSeat);
                    $amount += $sessionAreaFare->earlyTotalPrice;
                }
            }
            $order->amount = $amount;
            $order->save();

            foreach ($sessionSeats as $sessionSeat) {
                if ($sessionSeat) {
                    $ticket = new Ticket();
                    $ticket->order()->associate($order);
                    $ticket->session()->associate($session);
                    $ticket->sessionSeat()->associate($sessionSeat);
                    $sessionAreaFare = $sessionSeat['queuedAreaFare'];
                    $ticket->sessionAreaFare()->associate($sessionAreaFare);
                    $ticket->baseAmount = $sessionAreaFare->earlyPrice;
                    $ticket->baseAmountWithDiscount = $sessionAreaFare->earlyPrice;
                    $ticket->distributionAmount = $sessionAreaFare->earlyDistributionPrice;
                    $ticket->distributionAmountWithDiscount = $sessionAreaFare->earlyDistributionPrice;
                    $ticket->amount = $sessionAreaFare->earlyTotalPrice;
                    $ticket->amountWithDiscount = $sessionAreaFare->earlyTotalPrice;
                    $ticket->save();
                }
            }

            $paymentAttempt = new PaymentAttempt();
            $paymentAttempt->order()->associate($order);
            $paymentAttempt->amount = $amount;
            $paymentAttempt->status = 'successful';
            if ($request->filled('paymentMethod')) {
                $paymentAttempt->paymentMethod = $request->input('paymentMethod');
            }
            $paymentAttempt->save();

            // Modify the sessionSeats to set the status to 'hard-ticket'
            $sessionSeatIds = $order->tickets()->select('sessionSeatId')->get();
            $hardTicketCount = SessionSeat::whereIn('id', $sessionSeatIds)->update(['status' => 'hard-ticket', 'updatedAt' => Carbon::now()]);

            // Check if some sessionSeats couldn't be hard-ticketed, cause someone bought/reserved while doing it
            $gapCount = count($request->input('sessionSeatIds')) - $hardTicketCount;

            return response()->json(['order' => $order, 'gapCount' => $gapCount]);
        }
        return response()->json([], 400);
    }

    /**
     * Checks if the selected seats has the valid prices for the hard ticket emission.
     */
    public function checkPricesForHardTicket(Request $request, Show $show, Session $session)
    {
        $validPrices = true;

        $sessionSeats = collect();
        $amount = 0;
        foreach ($request->input('sessionSeatIds') as $sessionSeatId) {
            $sessionSeat = SessionSeat::whereId($sessionSeatId)
                ->where(static function ($builder) {
                    $builder->where('status', 'enabled')
                        ->orWhere('status', 'locked');
                })
                ->with('sessionSector')
                ->first();
            if ($sessionSeat) {
                $sessionAreaFare = SessionAreaFare::whereSessionAreaId($sessionSeat->sessionSector->sessionAreaId)
                    ->whereFareId($session->defaultFareId)
                    ->first();
                $sessionSeat['queuedAreaFare'] = $sessionAreaFare;
                $sessionSeats->push($sessionSeat);
            }
        }


        foreach ($sessionSeats as $sessionSeat) {
            if ($sessionSeat) {
                if (is_null($sessionAreaFare->earlyPrice) || is_null($sessionAreaFare->earlyDistributionPrice) ||
                    is_null($sessionAreaFare->earlyDistributionPrice)) {
                    $validPrices = false;
                    break;
                }
            }
        }
        return response()->json(['validPrices' => $validPrices], 200);
    }

    /**
     * @param $locator
     * @return JsonResponse
     */
    public function findByLocator($locator): JsonResponse
    {
        $order = Order::whereLocator($locator)->first();
        $order ? $check = true : $check = false;

        return response()->json(['check' => $check, 'order' => $order]);
    }

    /**
     * @param PointOfSale $pointOfSale
     * @param $locator
     * @return JsonResponse
     */
    public function pointOfSaleFindByLocator(PointOfSale $pointOfSale, $locator): JsonResponse
    {
        $order = Order::wherePointOfSaleId($pointOfSale->id)->whereLocator($locator)->first();
        $order ? $check = true : $check = false;

        return response()->json(['check' => $check, 'order' => $order]);
    }

    /**
     * @param PointOfSale $pointOfSale
     * @param $locatorOrNif
     * @return JsonResponse
     */
    public function pointOfSaleFindByLocatorOrNif(PointOfSale $pointOfSale, $locatorOrNif): JsonResponse
    {
        $orders = Order::select('Order.*')
            ->leftJoin('User', 'Order.userId', '=', 'User.id')
            ->where('Order.pointOfSaleId', $pointOfSale->id)
            ->where(static function ($query) use ($locatorOrNif) {
                $query->where('Order.locator', $locatorOrNif)
                    ->orWhere('User.nif', $locatorOrNif);
            })
            ->get();
        $ticket = Ticket::whereLocator($locatorOrNif)->first();

        $orders->count() || $ticket ? $check = true : $check = false;

        return response()->json(['check' => $check, 'orders' => $orders, 'ticket' => $ticket]);
    }
}
