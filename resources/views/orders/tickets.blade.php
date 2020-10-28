<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compra-{{$order->locator}}</title>
    <!-- Fonts -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/icon-hires.png')}}">
    @if(isset($order->tickets[0]->session->sessionPrintModel))
        @switch($order->tickets[0]->session->sessionPrintModel->font)
            @case('Roboto')
            <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
            @break
            @case('Lato')
            <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
            @break
            @case('Calibri')
            <link href="https://fonts.googleapis.com/css?family=Calibri:400,500,700&display=swap" rel="stylesheet">
            @break
            @case('Open-sans')
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,700&display=swap" rel="stylesheet">
            @break
        @endswitch
    @else
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
@endif
<!-- Styles -->
    <style>
        @page {
            size: 210mm 297mm;
            margin: 0;
            padding: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            border: none;
            background: none;
            color: black;
        }

        @if(isset($order->tickets[0]->session->sessionPrintModel))
        @switch($order->tickets[0]->session->sessionPrintModel->font)

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
            padding: 10mm;
            margin: 0;
            position: relative;
            overflow: hidden;
            /*width: 210mm;
            height: 297mm;*/
            display: block;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .ticket-info {
            display: block;
            width: 100%;
            height: 96mm;
            clear: both;
        }

        .ticket-info p {
            margin: 0;
        }

        .ticket-main-info {
            border: 3mm solid #cb2b99;
            /* 114 mm - 6 mm */
            width: 108mm;
            height: 90mm;
            display: inline-block;
            float: left;
        }

        .ticket-main-info .info {
            width: 98mm;
            height: 59mm;
            padding: 3mm;
            margin: 0;
        }

        .ticket-main-info .barcode-container {
            border-top: 3mm solid #cb2b99;
            padding: 3mm;
            width: 104mm;
            height: 16mm;
        }

        .ticket-main-info .barcode-container img {
            height: 16mm;
            max-width: 100mm;
        }

        .ticket-secondary-info {
            border: 3mm solid #b4b4b4;
            width: 70mm;
            height: 90mm;
            display: inline-block;
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

        .ticket-secondary-info .first-column .qr-container {
            padding: 2mm;
            width: 29mm;
            height: 28mm;
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

        .second-barcode-container {
            display: block;
            /* Note: for a CLOCKWISE rotation, use the commented-out
               transform instead of this one. */
            transform: rotate(-90deg) translate(-28%, 30%);
            /* transform: rotate(90deg) translate(0, -100%); */

            /* Not vital, but possibly a good idea if the element you're rotating contains
               text and you want a single long vertical line of text and the pre-rotation
               width of your element is small enough that the text wraps: */
            white-space: nowrap;
            height: 34mm;
            width: 90mm;
            font-size: 10px;
        }

        .second-barcode-container p {
            margin: 0;
            line-height: 1;
        }

        .second-barcode-container img {
            height: 12mm;
            max-width: 82mm;
            margin-top: 4mm;
        }

        .ticket-tip {
            width: 100%;
            font-weight: 500;
            text-align: center;
            font-size: 13px;
            margin: 10px 0;
        }

        .ticket-instructions {
            width: 100%;
            border: 2px solid black;
            padding: 10px 14px;
        }

        .ticket-instructions .ticket-instructions-title {
            width: 100%;
            font-weight: 700;
            text-align: center;
            font-size: 14px;
        }

        .ticket-instructions p {
            font-size: 11px;
            text-align: justify;
            line-height: 1;
            margin: 7px 0;
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
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .ticket-info .show-date {
            text-transform: uppercase;
            font-size: 14px;
            margin-bottom: 10px;
            position: relative;
        }

        .ticket-info .show-hour {
            position: absolute;
            right: 10mm;
        }

        .ticket-info .place-name {
            font-size: 13px;
            margin-bottom: 2px;
        }

        .ticket-info .place-province {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .ticket-info .space-name {
            font-size: 18px;
            margin-bottom: 2px;
            font-weight: 500;
        }

        .ticket-info .session-seat-row {
            font-size: 18px;
            font-weight: 500;
        }

        .footer {
            width: 100%;
            position: relative;
            height: 70px;
        }

        /*
        .footer-qr-container {
            width: 300px;
            height: 64px;
            position: absolute;
            left: 0;
            bottom: 50px;
        }

        .footer-qr-container .footer-qr-box {
            display: inline-block;
            float: left;
            max-height: 64px;
        }
        .footer-qr-container .footer-qr-box img {
            max-height: 64px;
        }
        */
        .app-logo-container {
            width: 256px;
            height: 64px;
            position: absolute;
            right: 0;
            bottom: 50px;
        }

        .app-logo-tip {
            display: inline-block;
            float: left;
            width: 94px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 44px;
        }

        .app-logo {
            display: inline-block;
            float: left;
            max-width: 162px;
            height: 64px;
        }
    </style>
</head>

<body>
@foreach($order->tickets as $ticket)
    <div class="ticket-container">
        <div class="ticket-info">
            <div class="ticket-main-info">
                <div class="info">
                    <p class="show-name">
                        {{-- - {{$ticket->session->showTemplate->ticketName}} --}}
                        @if($ticket->session->show->ticketName && $ticket->session->show->ticketName !== $ticket->session->showTemplate->ticketName)
                            {{$ticket->session->show->ticketName}}
                        @else
                            {{$ticket->session->showTemplate->ticketName}}
                        @endif
                    </p>
                    <p class="show-date">
                        {{$ticket->session->date->dayName}} {{$ticket->session->date->format('d-M-Y')}}
                        <span class="show-hour">{{$ticket->session->date->format('H:i')}} </span>
                    </p>
                    <p class="place-name">{{$ticket->session->place->ticketName}}</p>
                    <p class="place-province">{{$ticket->session->place->province->name}}
                        , {{$ticket->session->place->city}}</p>
                    @if($ticket->sessionSeat->sessionSector)
                        <p class="space-name">{{$ticket->sessionSeat->sessionSector->sessionArea->space->ticketName}}
                            - {{$ticket->sessionSeat->sessionSector->sessionArea->ticketName}}
                            &nbsp;{{$ticket->sessionSeat->sessionSector->ticketName}}</p>
                    @endif
                    @if($ticket->sessionSeat->rowName && $ticket->sessionSeat->number)
                        <p class="session-seat-row">
                            Fila: {{$ticket->sessionSeat->rowName}} - Butaca: {{$ticket->sessionSeat->number}}
                        </p>
                    @endif
                </div>
                <div class="barcode-container">
                    {!! $ticket['barcode'] !!}
                </div>
            </div>

            <div class="ticket-secondary-info">
                <div class="first-column">
                    <div class="show-image">
                        @if(isset($ticket->session->sessionPrintModel) && $ticket->session->sessionPrintModel->mainImageUrl)
                            <img src="{{$ticket->session->sessionPrintModel->mainImageUrl}}" alt="show">
                        @elseif($ticket->session->mainImageUrl)
                            <img src="{{$ticket->session->mainImageUrl}}" alt="show">
                        @endif
                    </div>
                    <div class="qr-container">
                        <img src="data:image/png;base64, {!! base64_encode($ticket['qrcode']) !!}" alt="qrcode">
                    </div>
                    <div class="price-info">
                        @if($order->type !== 'invitation')
                            <div class="price-title">ENTRADA</div>

                            <table class="price-table">
                                <tr>
                                    <td>Importe:</td>
                                    <td class="text-right">{{$ticket->baseAmountWithDiscount}} &euro;</td>
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
                        @if(isset($order->client) && $order->client)
                            <p>Cliente: {{$order->client->surname}}, {{$order->client->name}}</p>
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
        </div>

        <div class="ticket-tip">
            PARA AGILIZAR EL ACCESO, SE RUEGA QUE CADA ASISTENTE MUESTRE SU ENTRADA EN LA PUERTA DEL RECINTO
        </div>
        <div class="ticket-instructions">
            <div class="ticket-instructions-title">
                INSTRUCCIONES DE USO Y EXTRACTO DE LAS CONDICIONES GENERALES
            </div>
            <p>
                Este documento constituye una entrada electrónica y permite a su portador el acceso al espectáculo que
                figura en la misma. Cualquier intento de
                falsificación o duplicación de esta entrada será detectado al acceder al recinto. El comprador de este
                entrada, cuyo nombre aparece en la
                misma, y/o el titular de la tarjeta utilizada para el pago son responsables de forma solidaria ante la
                organizacian de cualquier intento de fraude.
            </p>
            <p>
                Esta entrada solo debe ser adquirida en canales de venta oficiales. Si compra esta entrada en puntos no
                autorizados, como sitios web de
                reventa, podría ser víctima de un fraude.
            </p>
            <p>
                Imprima esta entrada en papel blanco DIN A4, sin modificar la escala o el contenido del documento. No
                utilice modos de impresión rápida o
                borrador, ya que esto podría dificultar la lectura de los códigos de barra impresos que aparecen en la
                entrada.
            </p>
            <p>
                La tenencia y/o utilización de esta entrada supone la aceptación de las condiciones generales publicadas
                en la pagina web
                www.redentradas.com, de las cuales a continuación se reproduce lo esencial a modo de extracto.
            </p>
            <p>
                Según lo dispuesto en los términos de la legislación vigente en cada caso, una vez adquirida la entrada
                no será cambiada ni devuelto su importe,
                excepto en los caso de cancelación, suspensión, aplazamiento o modificación sustancial del evento.
            </p>
            <p>
                La salida del recinto implica la perdida del derecho a entrar nuevamente, salvo en aquellos eventos en
                los que el organizador disponga lo
                contrario. Es potestad del organizador permitir la entrada una vez comenzado el evento.
            </p>
            <p>
                Se reserva el derecho de admisión, condicionado al hecho de disponer de la entrada, y de que el portador
                asuma y se obligue a respetar las
                normas de la organización y del recinto, asi como cualquier condición exigible por motivos de seguridad
                general. El portador autoriza al
                organizador a efectuar, de acuerdo con la Ley, las revisiones o registros de su persona y tenencias para
                verificar que se cumplen las condiciones
                de seguridad.
            </p>
            <p>
                El organizador padrá denegar el acceso o expulsar del recinto al portador en caso de incumplimiento de
                las indicaciones del personal de la
                organización, así como en el caso de que pueda racionalmente presumirse que se va a crear una situación
                de riesgo o peligro para el propio
                portador u otros asistentes, de alboroto o por estados de intoxicación aparente o potencial,
                responsabilizándose personalmente el portador en
                todos los casos por sus propias acciones u omisiones que causen lesiones a terceros o daños a las cosas.
            </p>
            <p>
                La posesión de este entrada no da derecho a su poseedor o a terceros a utilizar la misma, o su
                contenido, con fines publicitarios, de marketing o
                de promoción (incluidos concursos, regalos y/o sorteos), asociada al poseedor de la entrada o tercero.
            </p>
            <p>
                Queda prohibida la filmación, grabación o reproducción en el interior del recinto salvo autorización
                expresa del organizador. Al efecto, se prohibe
                la entrada de cámaras de fotos, vídeo o aparatos de grabación de cualquier tipo, así como cualquier
                objeto que el organizador considere
                peligroso (pirotecnia, armas, botellas, objetos de cristal, punteros láser, etc.).
            </p>
        </div>

        <div class="footer">
        <!--
            <div class="footer-qr-container">
                <div class="footer-qr-box">
                    <img src="data:image/png;base64, {!! base64_encode($ticket['qrcode']) !!}" alt="qrcode">
                </div>
                <div class="footer-qr-box">
                    {!! $ticket['barcode'] !!}
                </div>
            </div>
-->
            <div class="app-logo-container">
                <span class="app-logo-tip">Distribuido por</span>
            <!--<img class="app-logo" src="{{asset('/images/logo.png')}}" alt="AppLogo">-->
                <img class="app-logo" src="https://staging.api.redentradas.beebit.es/images/logo.png" alt="AppLogo">
            </div>

            <!--<img class="app-logo" src="https://api.redentradas.beebit.es/images/logo.png" alt="AppLogo">-->
        </div>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
