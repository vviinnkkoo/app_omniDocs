<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Račun</title>

    @include('parts.pdf.style')

</head>
<body>

    @include('parts.pdf.invoice-header')

    {{-- PDF content - START --}}

    <div class="margin-first">
        <table class="w-full info">
            <tr>
                <td class="w-tri">
                    <div><h4>Kupac:</h4></div>
                    <div>{{ App\Models\Customer::find($order->customer_id)->name }}</div>
                    <div>{{ $order->delivery_address }}</div>
                    <div>{{ $order->delivery_city }}, {{ $order->delivery_postal }}</div>
                    <div>{{ App\Models\Country::find($order->delivery_country_id)->country_name }}</div>
                    <div style="margin-top:10px"><b>OIB: </b>{{ App\Models\Customer::find($order->customer_id)->oib }}</div>
                </td>
                <td class="w-tri">
                    <div><h4>Datum i vrijeme izdavanja:</h4></div>
                    <div>{{$appSettings['address_city']}}</div>
                    <div>{{ \Carbon\Carbon::parse($receipt->created_at)->format('d.m.Y') }}</div>
                    <div>u {{ \Carbon\Carbon::parse($receipt->created_at)->format('H:i') }}</div>
                    <div style="margin-top:10px"><b>Datum isporuke: </b>{{ \Carbon\Carbon::parse($order->date_sent)->format('d.m.Y') }}</div>
                    <div><b>Datum dospijeća: </b>{{ \Carbon\Carbon::parse($receipt->created_at)->addDays(14)->format('d.m.Y') }}</div>
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

                    <td style="center">{{$item->product_id}}-{{$item->color_id}}</td>

                    <td>
                        {{ $item->productName }}<br>
                        <span style="font-size:70%">Boja: {{ $item->color }}</span>
                    </td>

                    <td class="center">
                        @if ($item->product == 'kom')
                            {{ number_format($item->amount), 0 }} {{ $item->product->unit }}
                        @else
                        {{ $item->amount }} {{ $item->product->unit }}
                        @endif
                    </td>

                    <td class="center">{{ number_format($item->price), 2, ',', '.' }} €</td>
                    <td class="center">{{ $item->discount }} %</td>
                    <td class="center">{{ $item->itemTotal }} €</td>                
                </tr>
            @endforeach

            {{-- Delivery service --}}
            <tr class="items" >
                <td></td>
                <td><b>Dostava: </b>{{ App\Models\DeliveryCompany::find($deliveryService->delivery_company_id)->name }} - {{ $deliveryService->name }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td  class="center">{{ $deliveryCost }} €</td>  
            </tr>

            {{-- Total --}}
            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="totalAmountText">Sveukupno: </td>
                <td class="totalAmount center"><b>{{ $total }} €</b></td>
            </tr>

        </table>
    </div>

    <div class="notes">
        <p><b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 1 Zakona o PDV-u.</p>
        <p><b>Način plaćanja:</b> {{ App\Models\PaymentType::find($order->payment_type_id)->type_name }} &nbsp;&nbsp; <b>Račun izdaje:</b> {{$appSettings['invoice_issuer_01']}}</p>
        <p><b>Poziv na broj:</b> 1512</p>
        <p><b>Broj narudžbe:</b> {{$order->id}}</p>
    </div>

    {{-- PDF content - END --}}

    @include('parts.pdf.signature-stamp')

</body>
</html>