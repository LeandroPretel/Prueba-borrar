<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hoja de Taquilla - Sesión: {{$session->webName}}</title>
    <!-- Fonts -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/icon-hires.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,700&display=swap" rel="stylesheet">

<!-- Styles -->
    <style>
        html {
            font-family: OpenSans, sans-serif;
            font-weight: 400;
            font-size: 14px;
        }

        table {
            width: 100%;
            background-color: white;
            box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .2), 0 8px 10px 1px rgba(0, 0, 0, .14), 0 3px 14px 2px rgba(0, 0, 0, .12);
            display: table;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        thead tr {
            height: 35px;
        }

        tbody tr {
            height: 50px;
        }

        tfoot tr {
            height: 50px;
        }

        tfoot td {
            font-size: 1.2rem;
            text-transform: uppercase;
            font-weight: bold;
            border-right: 1px solid rgba(0,0,0,.12);
        }

        tfoot td:first-of-type {
            text-align: right;
            padding-right: 5px;
        }

        tr {
            border-spacing: 0;
        }

        th {
            border: 1px solid rgba(0,0,0,.12);
            color: rgba(0,0,0,.6);
        }

        td {
            color: rgba(0,0,0,.87);
            border-bottom: 1px solid rgba(0,0,0,.12);
            padding: 10px 5px;
        }

        td:first-of-type {
            padding-left: 24px;
            border-left: 1px solid rgba(0,0,0,.12);
        }

        td:last-of-type {
            border-right: 1px solid rgba(0,0,0,.12);
        }

        .big-text {
            font-size: 1.6rem;
            letter-spacing: 12px;
            text-transform: uppercase;
        }

        .number {
            text-align: right;
            padding-right: 5px;
        }
    </style>
</head>

<body>
<table>
    <thead>
    <tr>
        <th rowspan="2">Tipo de venta</th>
        <th rowspan="2">Zona</th>
        <th colspan="2" class="big-text">Tarifa</th>
        <th colspan="2" class="big-text">Ventas</th>
    </tr>
    <tr>
        <th>Denominación</th>
        <th>PVP (IVA incl.)</th>
        <th>Uds.</th>
        <th>Total IVA incl.</th>
    </tr>
    </thead>

    <tbody>
    @foreach($reportLines as $reportLine)
        <tr>
            <td>{{$reportLine['via']}}</td>
            <td>{{$reportLine['sessionAreaName']}}</td>
            <td>{{$reportLine['fareName']}}</td>
            <td class="number">{{$reportLine['pvp']}}</td>
            <td class="number">{{$reportLine['ticketCount']}}</td>
            <td class="number">{{$reportLine['amount']}}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <td colspan="4">Total Sesión..............</td>
        <td class="number">{{$totalTickets}}</td>
        <td class="number">{{$totalAmount}}</td>
    </tr>
    </tfoot>
</table>
</body>
</html>
