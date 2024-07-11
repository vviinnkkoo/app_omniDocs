<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>P-10m etikete</title>

    <style>
        @page {
            margin: 22px 8px;
        }
        body {
            /* font-family:"DeJaVu Sans Mono",monospace; */
            font-family: 'DejaVu Sans';
        }
        .center {
            text-align: center;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .w-tri {
            width: 32%;
        }
        .page-break {
            page-break-before: always;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.82rem;
            /* border: dashed 3px black; */
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            line-height: 0.72rem;
        }
        th, tr {
            text-align: left;
        }

        .first-row {
            width:2%;
        }

        .second-row {
            width:25%;
        }

        .third-row {
            width:25%;
        }
        .fourth-row {
            width:30%;
        }

        .title {
            font-size: 0.95rem;
            font-weight: bold;
        }

        .otk {
            font-size: 1rem;
            font-weight: bold;
            background-color: black;
            color:white;
            padding: 6px;
            border-radius: 4px;
        }

        .logo {
            position: absolute;
        }
    </style>
</head>
<body>

    <div>

            <table class="products">

                <tr>
                    <td class="first-row"></td>
                    <td class="second-row"></td>
                    <td class="third-row"></td>
                </tr>

                <tr>
                    <td style="padding-top:38px; font-size:20px; font-weight:bold;"></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td style="padding-top:56px; font-size:20px;">Omnius Art</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td style="padding-top:8px; font-size:20px;">Vladimira Nazora 83</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td style="padding-top:9px; font-size:20px; letter-spacing: 8px;">31542</td>
                    <td style="font-size:20px;">Šljivoševci</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td style="padding-top:15px; font-size:20px;">0989050340</td>
                </tr>

                <tr>
                    <td></td>
                    <td style="padding-top:56px; font-size:20px;">{{ App\Models\Customer::find($order->customer_id)->name}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="2" style="padding-top:12px; font-size:20px;">{{ $order->delivery_address}}</td>
                </tr>

                <tr>
                    <td></td>
                    <td style="padding-top:19px; font-size:20px; letter-spacing: 8px;">{{ $order->delivery_postal}}</td>
                    <td style="font-size:20px;">{{ $order->delivery_city}}</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td style="padding-top:6px; font-size:20px;">{{ $order->delivery_phone}}</td>
                </tr>

                <tr>

                    <td style="padding-top:86px; font-size:20px; font-weight:bold;">
                        @if ( $order->payment_type_id == 2 )
                        X
                        @endif
                    </td>

                    <td style="padding-top:86px; font-size:20px; padding-left:145px;">
                        @if ( $order->payment_type_id == 2 )
                            {{ App\Http\Controllers\DoomPDFController::labelItemTotal($order->id) }} €
                        @endif
                    </td>

                    <td></td>
                </tr>

            </table>
    </div>

</body>
</html>