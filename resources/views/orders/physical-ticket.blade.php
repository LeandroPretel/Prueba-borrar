<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrada-{{$ticket->locator}}</title>
    <!-- Fonts -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/icon-hires.png')}}">
    @if(isset($ticket->session->sessionPrintModel))
        @switch($ticket->session->sessionPrintModel->font)
            @case('Roboto')
            <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
            @break
            @case('Lato')
            <link href="https://fonts.googleapis.com/css?family=Lato:300,400,500,700&display=swap" rel="stylesheet">
            @break
            @case('Calibri')
            <link href="https://fonts.googleapis.com/css?family=Calibri:300,400,500,700&display=swap" rel="stylesheet">
            @break
            @case('Open-sans')
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700&display=swap"
                  rel="stylesheet">
            @break
        @endswitch
    @else
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
@endif

<!-- Styles -->
    <style>
        @page {
            size: 152mm 73mm;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 152mm;
            height: 73mm;
            margin: 0;
            padding: 0;
            border: 1px solid red;
            background: none;
            color: black;
        }

        @if(isset($ticket->session->sessionPrintModel))
        @switch($ticket->session->sessionPrintModel->font)

        @case('Roboto')
        html, body {
            font-family: 'Roboto', sans-serif;
        }

        @break

        @case('Lato')
        html, body {
            font-family: 'Lato', sans-serif;
        }

        @break

        @case('Calibri')
          html, body {
            font-family: 'Calibri', sans-serif;
        }

        @break

        @case('Open-sans')
          html, body {
            font-family: 'OpenSans', sans-serif;
        }

        @break
        @endswitch
        @else
         html, body {
            font-family: 'Roboto', sans-serif;
        }

        @endif

        .ticket-container {
            padding: 3mm 11mm 3mm 2mm;
            margin: 0;
            position: relative;
            overflow: hidden;
            display: block;
            box-sizing: border-box;
            page-break-inside: avoid;
            height: 100%;
            width: 100%;
        }

        .ticket-info {
            width: 100mm;
            display: inline-block;
            float: left;
            border-bottom: 1px solid black;
            padding-top: 2mm;
        }

        .ticket-info p {
            margin: 0;
        }

        .ticket-main-info {
            display: inline-block;
            float: left;
            width: 61mm;
            margin-right: 2mm;
        }

        .show-image {
            display: inline-block;
            float: left;
            width: 37mm;
            height: 37mm;
        }

        .show-image img {
            width: 37mm;
            height: 37mm;
        }

        .barcode-container {
            display: inline-block;
            float: left;
            width: 39mm;
            height: 100%;
        }

        .barcode {
            display: block;
            transform: rotate(-90deg) translate(-44%, -15%);
            /* transform: rotate(90deg) translate(0, -100%); */

            /* Not vital, but possibly a good idea if the element you're rotating contains
               text and you want a single long vertical line of text and the pre-rotation
               width of your element is small enough that the text wraps: */
            white-space: nowrap;
            height: 15mm;
            width: 60mm;
        }

        .ticket-secondary-info {
            display: none;
            border: 3mm solid #b4b4b4;
            width: 70mm;
            height: 90mm;
            float: left;
        }

        .ticket-secondary-info .first-column {
            border-right: 3mm solid #b4b4b4;
            height: 90mm;
            width: 33mm;
            display: inline-block;
            float: left;
        }

        .ticket-secondary-info .first-column .show-image {
            width: 33mm;
            height: 32mm;
        }

        .ticket-secondary-info .first-column .show-image img {
            width: 33mm;
            height: 32mm;
        }

        .ticket-secondary-info .first-column .qr-container img {
            width: 29mm;
            height: 28mm;
        }

        .ticket-secondary-info .first-column .price-info {
            width: 29mm;
            height: 25mm;
            background: #3c3c3b;
            color: white;
            font-size: 10px;
            padding: 0.5mm 2mm;
        }

        .ticket-secondary-info .first-column .price-info p {
            margin: 0;
            line-height: 1;
        }

        .ticket-secondary-info .first-column .price-info .price-title {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
        }

        .price-table {
            width: 29mm;
            border-collapse: collapse;
        }

        .price-table td {
            padding: 0;
        }

        .ticket-secondary-info .second-column {
            height: 90mm;
            width: 34mm;
            display: inline-block;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }

        .ticket-info .show-name {
            font-size: 4.75mm;
            font-weight: 500;
            padding-bottom: 4px;
            text-transform: uppercase;
            border-bottom: 1px solid black;
        }

        .ticket-info .show-date {
            text-transform: uppercase;
            font-size: 3mm;
            margin-top: 0.8mm;
            margin-bottom: 1.6mm;
            position: relative;
        }

        .ticket-info .show-hour {
            text-transform: none;
            position: absolute;
            right: 6mm;
        }

        .ticket-info .place-name {
            font-size: 3mm;
            margin-bottom: 0.2mm;
            font-weight: 500;
        }

        .ticket-info .place-province {
            font-size: 3mm;
            margin-bottom: 2.6mm;
        }

        .ticket-info .space-name {
            font-size: 4.2mm;
            margin-bottom: 1mm;
        }

        .ticket-info .session-seat-row {
            font-size: 4.75mm;
            font-weight: 500;
            background: black;
            color: white;
        }

        .ticket-info .enterprise-name {
            font-size: 2.4mm;
            margin-bottom: 0.4mm;
            margin-top: 3mm;
            font-weight: 300;
        }

        .ticket-info .price {
            font-size: 2.4mm;
            font-weight: 500;
            margin-bottom: 0.4mm;
        }
    </style>
</head>

<body>
<div class="ticket-container">
    <div class="ticket-info">
        <p class="show-name">
            {{-- -{{$ticket->session->showTemplate->ticketName}}--}}
            @if($ticket->session->show->ticketName && $ticket->session->show->ticketName !== $ticket->session->showTemplate->ticketName)
                {{$ticket->session->show->ticketName}}
            @else
                {{$ticket->session->showTemplate->ticketName}}
            @endif
        </p>
        <div class="ticket-main-info">
            <div class="ticket-main-info">
                <p class="show-date">
                    {{$ticket->session->date->dayName}} {{$ticket->session->date->format('d-M-Y')}}
                    <span class="show-hour">
                        {{$ticket->session->date->format('H:i')}} h
                    </span>
                </p>
                <p class="place-name">{{$ticket->session->place->ticketName}}</p>
                <p class="place-province">{{$ticket->session->place->province->name}}
                    , {{$ticket->session->place->city}}</p>
                @if($ticket->sessionSeat->sessionSector)
                    <p class="space-name">
                    <!--
                        {{$ticket->sessionSeat->sessionSector->sessionArea->space->ticketName}}
                            - {{$ticket->sessionSeat->sessionSector->sessionArea->ticketName}}
                            &nbsp;-->
                        {{$ticket->sessionSeat->sessionSector->ticketName}}
                    </p>
                @endif
                @if($ticket->sessionSeat->rowName && $ticket->sessionSeat->number)
                    <span class="session-seat-row">
                        Fila: {{$ticket->sessionSeat->rowName}} - butaca: {{$ticket->sessionSeat->number}}
                    </span>
                @endif
                <p class="enterprise-name">
                    {{$ticket->order->account->name}}
                    @if($ticket->order->account->enterprises[0])
                        &nbsp;-&nbsp;CIF:&nbsp;{{$ticket->order->account->enterprises[0]->nif}}
                    @endif
                </p>
                <p class="price">
                    @if($ticket->order->type !== 'invitation')
                        PVP: {{$ticket->baseAmountWithDiscount}}&euro; - G.D.:
                        {{$ticket->distributionAmountWithDiscount}}&euro; - Total IVA incl:
                        {{$ticket->amountWithDiscount}}&euro;
                    @else
                        INVITACIÓN. Prohibida su venta
                    @endif
                </p>
            </div>
        </div>
        <div class="show-image">
            @if(isset($ticket->session->sessionPrintModel) && $ticket->session->sessionPrintModel->mainImageUrl)
                <img src="{{$ticket->session->sessionPrintModel->mainImageUrl}}" alt="show">
            @elseif($ticket->session->mainImageUrl)
                <img src="{{$ticket->session->mainImageUrl}}" alt="show">
            @endif
        </div>
    </div>
    <div class="barcode-container">
        {!! $ticket['barcode'] !!}
    </div>
</div>


<div class="ticket-secondary-info">
    <div class="first-column">
        <div class="price-info">
            @if($ticket->order->type !== 'invitation')
                <div class="price-title">ENTRADA</div>

                <table class="price-table">
                    <tr>
                        <td>Importe:</td>
                        <td class="text-right">{{$ticket->baseAmountWithDiscount}}&euro;</td>
                    </tr>
                    <tr>
                        <td>Gastos distr:</td>
                        <td class="text-right">{{$ticket->distributionAmountWithDiscount}} &euro;</td>
                    </tr>
                    <tr>
                        <td><b>Total:</b></td>
                        <td class="text-right"><b>{{$ticket->amountWithDiscount}} &euro;</b></td>
                    </tr>
                </table>
                <div class="text-center">IVA INCLUIDO</div>
            @else
                <div class="price-title">INVITACIÓN</div>
                <div class="text-center">Prohibida su venta</div>
            @endif
        </div>
    </div>
    <div class="second-column">
        <div class="second-barcode-container">
            @if(isset($ticket->order->client) && $ticket->order->client)
                <p>Cliente: {{$ticket->order->client->surname}}, {{$ticket->order->client->name}}</p>
            @endif
            <p>Localizador: {{$ticket->locator}}</p>
            <p>
                Organizador: {{$ticket->order->account->name}}
                @if($ticket->order->account->enterprises[0])
                    &nbsp;-&nbsp;{{$ticket->order->account->enterprises[0]->nif}}
                @endif
            </p>
            {!! $ticket['barcode'] !!}
        </div>
    </div>
</div>
</body>
</html>
