<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderReturn;
use App\Session;
use App\Ticket;
use DNS1D;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Savitar\Auth\Traits\Authorization;
use Savitar\Crud\Traits\CRUD;
use Savitar\DataGrid\Traits\DataGrid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class TicketController extends Controller
{
    use DataGrid;
    use CRUD;
    use Authorization;

    /**
     * TicketController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Ticket::class);
        $this->configureCRUD([
            'modelClass' => Ticket::class,
        ]);
        $this->configureDataGrid([
            'modelClass' => Ticket::class,
            'dataGridTitle' => 'Entradas',
            'defaultOrderBy' => 'number',
            'dataGridWithTrashed' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function invitationsDataGrid(Request $request)
    {
        $this->setDataGridConditions([
            ['column' => 'Ticket.orderId', 'operator' => '=', 'value' => null],
            ['column' => 'Ticket.orderReturnId', 'operator' => '=', 'value' => null],
        ]);
        $this->setDataGridWithTrashed(false);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function sessionInvitationsDataGrid(Request $request, Session $session)
    {
        $this->setDataGridConditions([
            ['column' => 'Ticket.orderId', 'operator' => '=', 'value' => null],
            ['column' => 'Ticket.sessionId', 'operator' => '=', 'value' => $session->id],
            ['column' => 'Ticket.orderReturnId', 'operator' => '=', 'value' => null],
        ]);
        $this->setDataGridWithTrashed(false);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function orderTicketsDataGrid(Request $request, Order $order)
    {
        $this->setDataGridConditions([
            ['column' => 'orderId', 'operator' => '=', 'value' => $order->id],
            ['column' => 'orderReturnId', 'operator' => '=', 'value' => null],
        ]);
        return $this->dataGrid($request);
    }

    /**
     * @param Request $request
     * @param OrderReturn $orderReturn
     * @return array|bool|ResponseFactory|Response
     * @throws JWTException
     */
    public function orderReturnTicketsDataGrid(Request $request, OrderReturn $orderReturn)
    {
        $this->setDataGridConditions([
            ['column' => 'orderReturnId', 'operator' => '=', 'value' => $orderReturn->id],
        ]);
        return $this->dataGrid($request);
    }

    /**
     * @param Ticket $ticket
     * @return mixed
     */
    public function downloadTicket(Ticket $ticket)
    {
        $ticket->loadMissing([
            'order.account.enterprises',
            'order.client',
            'session.show',
            'session.showTemplate',
            'session.sessionPrintModel',
            'session.place.province',
            'sessionSeat.sessionSector.sessionArea.space',
        ]);
        // $session->makeHidden(['show', 'place']);

        // Generates qrcode and barcode for each ticket locator
        $ticket['barcode'] = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($ticket->locator, 'C128') . '" alt="barcode"/>';
        $ticket['qrcode'] = QrCode::format('png')->size(120)->margin(0)->generate($ticket->locator);
        //$qrcode = '<img src="data:image/png;base64,' . QrCode::size(200)->generate($order->id) . '" alt="qrcode"   />';

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('orders.ticket', [
            'ticket' => $ticket,
        ]);

        //return view('orders.ticket', ['ticket' => $ticket]);

        return $pdf->download('Entrada-' . $ticket->locator . '.pdf');
    }

    /**
     * @param Ticket $ticket
     * @return mixed
     */
    public function downloadPhysicalTicket(Ticket $ticket)
    {
        /*
        \DB::enableQueryLog();

        $order = Order::where('id', $ticket->orderId)->with([ 'account' => function ($query2) {
            $query2->with(['enterprises']);
        }, 'client'])->first();

        $order2 = Order::where('Order.id', $ticket->orderId)
            ->join('Account', 'Order.accountId', '=', 'Account.id')
            ->first();
        dd(\DB::getQueryLog(), $order->toArray(), $order2->toArray());
        // $session->makeHidden(['show', 'place']);
         'order' => function ($query) {
                $query->with([
                    'account' => function ($query2) {
                        $query2->with(['enterprises']);
                    }, 'client'
                ]);
            },
        */
        $ticket->loadMissing([
            'order.account.enterprises',
            'order.client',
            'session.show',
            'session.showTemplate',
            'session.sessionPrintModel',
            'session.place.province',
            'sessionSeat.sessionSector.sessionArea.space',
        ]);
        // Generates qrcode and barcode for each ticket locator
        $ticket['barcode'] = '<img class="barcode" src="data:image/png;base64,' . DNS1D::getBarcodePNG($ticket->locator, 'C128') . '" alt="barcode"/>';
        $ticket['qrcode'] = QrCode::format('png')->size(120)->margin(0)->generate($ticket->locator);
        //$qrcode = '<img src="data:image/png;base64,' . QrCode::size(200)->generate($order->id) . '" alt="qrcode"   />';

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('orders.physical-ticket', [
            'ticket' => $ticket,
        ]);

        return view('orders.physical-ticket', ['ticket' => $ticket]);

        //return $pdf->download('Entrada-' . $ticket->locator . '.pdf');
    }

    /**
     * @param $locator
     * @return JsonResponse
     */
    public function findByLocator($locator): JsonResponse
    {
        $ticket = Ticket::whereLocator($locator)->first();
        $ticket ? $check = true : $check = false;

        return response()->json(['check' => $check, 'ticket' => $ticket]);
    }
}
