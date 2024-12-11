<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ponuda br: {{$order->id}}</title>

    @include('parts.pdf-invoice-style')

</head>
<body>

    <!-- Define header and footer blocks before your content -->
    <header>
        <table class="w-full" style="border-bottom: solid 1px black">
            <tr>
                <td class="w-half">
                    {{--<img src="{{ asset('images/omnius-art-logo.png') }}" alt="Omnius Art" height="54" />--}}
                    <img src="{{ asset($appSettings['invoice_logo']) }}" height="54" />
                </td>
                <td class="w-half">
                    <h2>PONUDA br: <span class="gray-overlay">{{$order->id}}</span></h2>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="center footer-content">
            <div><b>{{$appSettings['company_name']}}</b>, {{$appSettings['company_extra_info']}} | Adresa vlasnika: <b>{{$appSettings['address']}}, {{$appSettings['address_city']}}</b> | OIB: <b>{{$appSettings['company_oib']}}</b></div>
            <div>Porezni broj: <b>{{$appSettings['company_vat_id']}}</b> | Žiro račun IBAN: <b>{{$appSettings['company_iban']}}</b> otvoren u: <b>{{$appSettings['company_bank']}}</b></div>
        </div>
    </footer>




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

                    <td style="center">{{$item->product_id}}-{{$item->color_id}}</td>

                    <td>
                        {{ App\Models\Product::find($item->product_id)->product_name }}<br>
                        <span style="font-size:70%">Boja: {{ App\Models\Color::find($item->color_id)->color_name }}</span>
                    </td>

                    <td class="center">
                        @if (App\Models\Product::find($item->product_id)->unit == 'kom')
                            {{ number_format(str_replace(',', '.', $item->amount), 0) }} {{ App\Models\Product::find($item->product_id)->unit }}
                        @else
                        {{ $item->amount }} {{ App\Models\Product::find($item->product_id)->unit }}
                        @endif
                    </td>

                    <td class="center">{{ number_format(str_replace(',', '.', $item->price), 2, ',', '.') }} €</td>
                    <td class="center">{{ $item->discount }} %</td>
                    <td class="center">{{ App\Http\Controllers\OrderItemListController::sumSingleItem($item->id) }} €</td>                
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
        <p><b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 2 Zakona o PDV-u</p>
        <p><b>Način plaćanja:</b> {{ App\Models\PaymentType::find($order->payment_type_id)->type_name }} &nbsp;&nbsp; <b>Račun izdaje:</b> {{$appSettings['invoice_issuer_01']}}</p>
        <p><b>Poziv na broj:</b> 1512</p>
        <p><b>Broj narudžbe:</b> {{$order->id}}</p>
    </div>

    <div class="margin-first">
        <table class="w-full info">
            <tr>
                <td class="w-tri">
                </td>
                <td class="w-tri center relative-section">
                    M.P.
                    <img src="{{ asset($appSettings['invoice_stamp']) }}" alt="Omnius Art" height="50" class="mp-image" />
                </td>
                <td class="w-tri center relative-section">
                    <div style="border-top: solid 1px black;">Potpis</div>
                    <img src="{{ asset($appSettings['invoice_signature']) }}" alt="Omnius Art" height="50" class="signature-image" />
                </td>
            </tr>
        </table>
    </div>

</body>
</html>