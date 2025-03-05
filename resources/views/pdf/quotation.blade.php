<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ponuda br: {{$order->id}}</title>

    @include('parts.pdf.style')

</head>
<body>

    @include('parts.pdf.quotation-header')

    {{-- PDF content - START --}}

    <div class="margin-first">
        <table class="w-full info">
            <tr>
                <td class="w-tri">
                    <div><h4>Kupac:</h4></div>
                    <div>{{ $orderData['customerName'] }}</div>
                    <div>{{ $order->delivery_address }}</div>
                    <div>{{ $order->delivery_city }}, {{ $order->delivery_postal }}</div>
                    <div>{{ $orderData['countryName'] }}</div>
                    <div style="margin-top:10px"><b>OIB: </b>{{ $orderData['customerOib'] }}</div>
                </td>
                <td class="w-tri">
                    <div><h4>Datum i vrijeme izdavanja:</h4></div>
                    <div>{{$appSettings['address_city']}}</div>
                    <div>{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y') }}</div>
                    <div>u {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</div>
                    <div><b>Ponuda vrijedi do: </b>{{ \Carbon\Carbon::parse($order->created_at)->addDays(14)->format('d.m.Y') }}</div>
                </td>
                <td class="w-tri">
                    <div><h4>Kontakt:</h4></div>
                    <div><b>Email:</b> {{$appSettings['contact_email']}}</div>
                    <div><b>Mob:</b> {{$appSettings['contact_phone']}}</div>
                </td>
                
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="products">
            <tr>
                <th>Šifra</th>
                <th>Naziv</th>
                <th>Količina</th>
                <th>Cijena</th>
                <th>Popust</th>
                <th>Iznos</th>
            </tr>

            {{-- Order items loop --}}
            @foreach ($orderItemList as $item)
                <tr class="items">

                    <td style="center">{{$item->productID}}-{{$item->colorID}}</td>

                    <td>
                        {{ $item->productName }}<br>
                        <span style="font-size:70%">Boja: {{ $item->colorName }}</span>
                    </td>

                    <td class="center">
                        @if ($item->productUnit == 'kom')
                            {{ number_format($item->amount, 0) }} {{ $item->productUnit }}
                        @else
                        {{ $item->amount }} {{ $item->productUnit }}
                        @endif
                    </td>

                    <td class="center">{{ number_format($item->price, 2, ',', '.') }} €</td>
                    <td class="center">{{ $item->discount }} %</td>
                    <td class="center">{{ number_format($item->total, 2, ',', '.') }} €</td>                
                </tr>
            @endforeach

            {{-- Delivery service --}}
            <tr class="items" >
                <td></td>
                <td><b>Dostava: </b>{{ $orderData['deliveryCompanyName'] }} - {{ $orderData['deliveryServiceName'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td  class="center">{{ number_format($deliveryCost, 2, ',', '.') }} €</td>  
            </tr>

            {{-- Total --}}
            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="totalAmountText">Sveukupno: </td>
                <td class="totalAmount center"><b>{{ number_format($total, 2, ',', '.') }} €</b></td>
            </tr>

        </table>
    </div>

    <div class="notes">
        <p><b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 1 Zakona o PDV-u.</p>
        <p><b>Način plaćanja:</b> {{ $orderData['paymentType'] }} &nbsp;&nbsp; <b>Ponudu izdaje:</b> {{$appSettings['invoice_issuer_01']}}</p>
        <p><b>Poziv na broj:</b> 1512</p>
        <p><b>Broj narudžbe:</b> {{$order->id}}</p>
    </div>

    {{-- PDF content - END --}}

    @include('parts.pdf.signature-stamp')

</body>
</html>