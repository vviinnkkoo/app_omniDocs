<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Dostavne etikete</title>

    <style>
        @page {
            margin: 100px 25px;
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
        .margin-top {
            margin-top: -3rem;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.82rem;
            border: dashed 3px black;
            padding: 0.5rem;
            margin-bottom: 1.2rem;
            line-height: 0.72rem;
        }
        th, tr {
            text-align: left;
        }
a
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

        .page_break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <div class="margin-top">

        @php
        $itemCount = 0
        @endphp

        @foreach ($shippingLabels as $item)

        

        @php
        $itemCount++;
        @endphp

        @if ($itemCount == 5 || $itemCount == 9 || $itemCount == 13 )
            <div class="page_break"></div>
        @endif 
        
            <table class="products">

                <tr>
                    <td class="first-row"></td>
                    <td class="second-row"></td>
                    <td class="third-row"></td>
                    <td class="fourth-row"></td>
                    <td class="fifth-row"></td>
                </tr>

                <tr>
                    <td></td>
                    <td class="title">Šalje:</td>
                    <td><img src="{{ asset('images/omnius-art-logo.png') }}" alt="Omnius Art" height="50" class="logo" /></td>
                    <td></td>
                    <td>Br. narudžbe: {{ $item->order_id }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td>Omnius Art</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>Vladimira Nazora 83</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>31542, Šljivoševci</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="title">Prima:</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ App\Models\Customer::find(App\Models\Order::find($item->order_id)->customer_id)->name }}</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        @if ( App\Models\Order::find($item->order_id)->payment_type_id == 2 )
                            <span class="otk">OTK: {{ App\Http\Controllers\DoomPDFController::labelItemTotal($item->order_id) }} €</span>
                        @endif
                    </td>
                    <td></td>
                    <td>{{ App\Models\Order::find($item->order_id)->delivery_address }}</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2">{{ App\Models\Order::find($item->order_id)->delivery_postal }}, {{ App\Models\Order::find($item->order_id)->delivery_city }}, <b>{{ App\Models\Country::find(App\Models\Order::find($item->order_id)->delivery_country_id)->country_name }}</b></td>
                    
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ App\Models\Order::find($item->order_id)->delivery_phone }}</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </table>            
            
        @endforeach       

    </div>

</body>
</html>