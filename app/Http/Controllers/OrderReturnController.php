<?php

namespace App\Http\Controllers;

use App\OrderReturn;
use App\PointOfSale;
use App\TicketSeason;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use Sermepa\Tpv\Tpv;
use Sermepa\Tpv\TpvException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderReturnController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * OrderReturnController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(OrderReturn::class);
        $this->configureCRUD([
            'modelClass' => OrderReturn::class,
            'indexConditions' => [
                ['column' => 'OrderReturn.status', 'operator' => '<>', 'value' => 'attempt'],
            ],
            'showAppends' => [
                'tickets.sessionSeat.sector',
                'pointOfSale',
                'OrderReturnReason',
            ],
            'defaultOrderBy' => 'date',
            'defaultSortOrder' => 'desc',
        ]);
        $this->configureDataGrid([
            'modelClass' => OrderReturn::class,
            'dataGridTitle' => 'Devoluciones',
            'defaultOrderBy' => 'date',
            'defaultSortOrder' => 'desc',
            'dataGridConditions' => [
                ['column' => 'OrderReturn.status', 'operator' => '<>', 'value' => 'attempt'],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param PointOfSale $pointOfSale
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function pointOfSaleOrderReturnsDataGrid(Request $request, PointOfSale $pointOfSale)
    {
        $this->setDataGridConditions([
            ['column' => 'OrderReturn.pointOfSaleId', 'operator' => '=', 'value' => $pointOfSale->id],
            ['column' => 'OrderReturn.status', 'operator' => '<>', 'value' => 'attempt'],
        ]);
        // 'userName',
        $this->setDefaultColumns(['mode', 'orderReturnReasonName', 'date', 'status', 'ticketsCount', 'amount']);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    public function redsysStore(Request $request): void
    {
        /** @var OrderReturn $orderReturn */
        $orderReturn = new $this->modelClass();
        $orderReturn->redsysNumber = time();
        $this->applyRequestToModel($request, $orderReturn, 'save');

        // Open Redsys
        try {
            $redsys = new Tpv();
            $key = config('redsys.key');

            // $redsys->setEnvironment('live'); //Entorno produccion
            $redsys->setEnvironment('test'); //Entorno pruebas
            $redsys->setUrlOk(config('redsys.api_url') . '/api/v1/orok?t=false&orderReturnId=' . encrypt($orderReturn['id'])); //Url OK
            $redsys->setUrlKo(config('redsys.api_url') . '/api/v1/orerr?t=false&orderReturnId=' . encrypt($orderReturn['id'])); //Url KO
            $redsys->setNotification(config('redsys.api_url') . '/api/v1/ornotification?t=false&orderReturnId=' . encrypt($orderReturn['id'])); //Url de notificacion

            $redsys->setAmount($orderReturn->amount);
            $redsys->setOrder($request->input('redsysNumber'));
            $redsys->setMerchantcode(config('redsys.merchant_code')); //Reemplazar por el código que proporciona el banco
            $redsys->setCurrency('978');
            $redsys->setTransactiontype('3');
            $redsys->setTerminal('1');
            $redsys->setMethod('C'); //Solo pago con tarjeta, no mostramos iupay
            $redsys->setVersion('HMAC_SHA256_V1');
            $redsys->setTradeName('Redentradas');
            $redsys->setTitular('Redentradas');
            $redsys->setProductDescription('Devolución de entradas');
            $redsys->setAttributesSubmit('btn_submit', 'btn_id', 'Enviar', 'display:none');
            $signature = $redsys->generateMerchantSignature($key);
            $redsys->setMerchantSignature($signature);
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
     */
    public function redsysOk(Request $request)
    {
        $test = $request->query('t');
        $authorizationCode = null;
        $orderReturnId = decrypt($request->query('orderReturnId'));
        $orderReturn = OrderReturn::find($orderReturnId);

        if ($request->query('Ds_MerchantParameters')) {
            $decoded = json_decode(base64_decode($request->query('Ds_MerchantParameters')), false);
            if ($decoded) {
                $authorizationCode = $decoded->Ds_AuthorisationCode;
            }
        }
        // Logging
        Log::info('Redsys ha detectado una devolución correcta.');
        if ($test === 'false' || !$test) {
            $this->updateOrderReturn($orderReturn, 'successful', $authorizationCode);
        }
        return view('redsys.ok');
    }

    /**
     * Redsys error function
     *
     * @param Request $request
     * @return Factory|View
     */
    public function redsysError(Request $request)
    {
        $test = $request->query('t');
        $authorizationCode = null;
        $orderReturnId = decrypt($request->query('orderReturnId'));
        $orderReturn = OrderReturn::find($orderReturnId);

        if ($request->query('Ds_MerchantParameters')) {
            $decoded = json_decode(base64_decode($request->query('Ds_MerchantParameters')), false);
            if ($decoded) {
                $authorizationCode = $decoded->Ds_AuthorisationCode;
            }
        }
        // Logging
        Log::info('Redsys ha detectado una devolución incorrecta.');
        if ($test === 'false' || !$test) {
            $this->updateOrderReturn($orderReturn, 'failed', $authorizationCode);
        }
        return view('redsys.error');
    }

    /**
     * Redsys notification function
     *
     * @param Request $request
     * @return Factory|View|int
     */
    public function redsysNotification(Request $request)
    {
        // $test = $request->query('t');
        $authorizationCode = null;
        $orderReturnId = decrypt($request->query('orderReturnId'));
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
            if ($DsResponse <= 99 && $redsys->check($key, $_POST)) {
                // Logging
                Log::info('Notificación redsys, devolución correcta (' . $authorizationCode . ')');
                // Acciones a realizar si es correcto, por ejemplo validar una reserva, mandar un mail de OK, guardar en bbdd o contactar con mensajería para preparar un pedido
                $orderReturn = OrderReturn::find($orderReturnId);
                if ($orderReturn && $orderReturn->status === 'successful') {
                    if (isset($authorizationCode)) {
                        $orderReturn->authorizationCode = $authorizationCode;
                        $orderReturn->save();
                    }
                } else {
                    $this->updateOrderReturn($orderReturn, 'successful', $authorizationCode);
                }
                return view('redsys.ok');
            }
            // Logging
            Log::info('Notificación redsys, devolución incorrecta (' . $authorizationCode . ')');
            // Acciones a realizar si ha sido erroneo
            $orderReturn = OrderReturn::find($orderReturnId);
            if ($orderReturn) {
                if ($orderReturn->status === 'successful') {
                    $orderReturn->status = 'failed';
                }
                if (isset($authorizationCode)) {
                    $orderReturn->authorizationCode = $authorizationCode;
                }
                $orderReturn->save();
            } else {
                $this->updateOrderReturn($orderReturn, 'failed', $authorizationCode);
            }
            return view('redsys.error');
        } catch (TpvException $tpvException) {
            Log::error($tpvException->getMessage());
            echo $tpvException->getMessage();
        }
        return 200;
    }

    /**
     * @param OrderReturn $orderReturn
     * @param string $status
     * @param null $authorizationCode
     */
    public function updateOrderReturn(OrderReturn $orderReturn, $status = 'successful', $authorizationCode = null): void
    {
        $token = JWTAuth::getToken();
        if ($token) {
            JWTAuth::setToken($token);
            $payload = JWTAuth::getPayload();
            $payload['impersonated'] ? $current = 'superadmin@redentradas.es' : $current = $payload['email'];
            $orderReturn->createdBy = $current;
            $orderReturn->updatedBy = $current;
        }
        $orderReturn->status = $status;
        if ($authorizationCode) {
            $orderReturn->authorizationCode = $authorizationCode;
        }
        $orderReturn->save();
    }

    /**
     * @return string
     */
    public function test(): string
    {
        $query = TicketSeason::whereHas('ticketSeasonOrders', function (Builder $query) {
            $query->leftJoin('sessions', 'ticketSessionOrders.sessionId', '=', 'sessions.id')
                ->havingRaw('SUM(sessions.duration) > ?', [2500]);
        })->toSql();
        return $query;
    }
}
